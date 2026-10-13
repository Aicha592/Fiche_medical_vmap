<?php

namespace App\Support;

use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use RuntimeException;
use XMLWriter;
use ZipArchive;

/** Writes worksheet XML to disk; memory usage is independent of the row count. */
class StreamingXlsxWriter
{
    public function write(array $sheets, array $metadata = []): string
    {
        $path = tempnam(sys_get_temp_dir(), 'vmap-xlsx-');
        $temporary = [];
        $zip = new ZipArchive;
        $counts = [];
        $opened = false;
        $hash = hash_init('sha256', HASH_HMAC, (string) config('app.key'));
        hash_update($hash, json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        try {
            if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Impossible de créer le fichier Excel.');
            }
            $opened = true;
            foreach ($sheets as $index => $sheet) {
                $xmlPath = tempnam(sys_get_temp_dir(), 'vmap-sheet-');
                $temporary[] = $xmlPath;
                $counts[$sheet['title']] = $this->worksheet($xmlPath, $sheet['headers'], $sheet['rows'], $hash, $sheet['status_columns'] ?? [], $sheet['centered_columns'] ?? [], $sheet['auto_row_height'] ?? false, $sheet['date_columns'] ?? [], $sheet['banded_rows'] ?? false);
                $zip->addFile($xmlPath, 'xl/worksheets/sheet'.($index + 1).'.xml');
            }
            $authRows = [
                ['AUTHENTIFICATION DU FICHIER EXPORTÉ', ''],
                ['Référence unique', $metadata['reference'] ?? ''],
                ['Date de génération', $metadata['generated_at'] ?? ''],
                ['Généré par', $metadata['generated_by'] ?? ''],
            ];
            foreach ($counts as $title => $count) {
                $authRows[] = ['Nombre de lignes '.$title, (string) $count];
            }
            $authRows[] = ['Algorithme', 'HMAC-SHA256 (métadonnées, en-têtes et lignes JSON successifs)'];
            $authRows[] = ['Signature des données', hash_final($hash)];
            $authPath = tempnam(sys_get_temp_dir(), 'vmap-sheet-');
            $temporary[] = $authPath;
            $this->worksheet($authPath, [], $authRows);
            $sheets[] = ['title' => 'Authentification', 'hidden' => true];
            $zip->addFile($authPath, 'xl/worksheets/sheet'.count($sheets).'.xml');

            $types = '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';
            $workbook = '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets>';
            $rels = '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
            foreach ($sheets as $index => $sheet) {
                $id = $index + 1;
                $types .= '<Override PartName="/xl/worksheets/sheet'.$id.'.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
                $workbook .= '<sheet name="'.htmlspecialchars($sheet['title'], ENT_XML1 | ENT_QUOTES, 'UTF-8').'" sheetId="'.$id.'" r:id="rId'.$id.'"'.(! empty($sheet['hidden']) ? ' state="hidden"' : '').'/>';
                $rels .= '<Relationship Id="rId'.$id.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet'.$id.'.xml"/>';
            }
            $zip->addFromString('[Content_Types].xml', $types.'</Types>');
            $zip->addFromString('_rels/.rels', '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
            $zip->addFromString('xl/workbook.xml', $workbook.'</sheets></workbook>');
            $zip->addFromString('xl/_rels/workbook.xml.rels', $rels.'<Relationship Id="styles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
            $zip->addFromString('xl/styles.xml', $this->styles());
            if (! $zip->close()) {
                throw new RuntimeException('Impossible de finaliser le fichier Excel.');
            }

            $opened = false;

            return $path;
        } catch (\Throwable $exception) {
            if ($opened) {
                @$zip->close();
            }
            @unlink($path);
            throw $exception;
        } finally {
            foreach ($temporary as $file) {
                @unlink($file);
            }
        }
    }

    private function styles(): string
    {
        $formats = [
            '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0"/>',
            '<xf numFmtId="49" fontId="1" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="1" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="1" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment wrapText="1" horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment wrapText="1" horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="49" fontId="1" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" horizontal="center" vertical="center"/></xf>',
            '<xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1" applyAlignment="1"><alignment wrapText="1" horizontal="center" vertical="center"/></xf>',
        ];
        $bandedFormats = '';
        foreach ([3, 4] as $fill) {
            foreach ($formats as $format) {
                $bandedFormats .= str_replace(['fillId="0"', '<xf '], ['fillId="'.$fill.'"', '<xf applyFill="1" '], $format);
            }
        }

        return '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><numFmts count="1"><numFmt numFmtId="164" formatCode="dd/mm/yyyy"/></numFmts><fonts count="4"><font><sz val="12"/><name val="Arial"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="12"/><name val="Arial"/></font><font><b/><color rgb="FF008000"/><sz val="14"/><name val="DejaVu Sans"/></font><font><b/><color rgb="FFFF0000"/><sz val="14"/><name val="DejaVu Sans"/></font></fonts><fills count="5"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF356A45"/><bgColor indexed="64"/></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFE2F0D9"/><bgColor indexed="64"/></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFFFFFFF"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="1"><border/></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="39">'.implode('', $formats).$bandedFormats.'</cellXfs></styleSheet>';
    }

    private function worksheet(string $path, array $headers, iterable $rows, $hash = null, array $statusColumns = [], array $centeredColumns = [], bool $autoRowHeight = false, array $dateColumns = [], bool $bandedRows = false): int
    {
        $xml = new XMLWriter;
        if (! $xml->openUri($path)) {
            throw new RuntimeException('Impossible de créer la feuille Excel.');
        }
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('worksheet');
        $xml->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $xml->writeRaw('<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>');
        $xml->writeRaw('<sheetFormatPr defaultColWidth="24" defaultRowHeight="24"/><cols>');
        for ($column = 1; $column <= max(count($headers), 2); $column++) {
            $xml->writeRaw('<col min="'.$column.'" max="'.$column.'" width="24" customWidth="1" bestFit="0"/>');
        }
        $xml->writeRaw('</cols><sheetData>');
        $number = 0;
        if ($hash) {
            hash_update($hash, json_encode($headers, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        }
        if ($headers) {
            $this->row($xml, ++$number, $headers, true, [], $centeredColumns, $autoRowHeight);
        }
        $count = 0;
        foreach ($rows as $row) {
            if ($hash) {
                hash_update($hash, json_encode($row, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
            }
            $this->row($xml, ++$number, $row, false, $statusColumns, $centeredColumns, $autoRowHeight, $dateColumns, $bandedRows);
            $count++;
            if ($count % 500 === 0) {
                $xml->flush();
            }
        }
        $xml->writeRaw('</sheetData>');
        if ($headers) {
            $xml->writeRaw('<autoFilter ref="A1:'.$this->column(count($headers)).max($number, 1).'"/>');
        }
        $xml->endElement();
        $xml->endDocument();
        $xml->flush();

        return $count;
    }

    private function rowHeight(array $values): float
    {
        $maxLines = 1;
        foreach ($values as $value) {
            $lines = 0;
            foreach (preg_split('/\R/u', (string) $value) ?: [''] as $line) {
                // Conservative wrapping estimate for the fixed 24-character columns.
                $used = 0;
                $lines++;
                foreach (preg_split('/\s+/u', trim($line)) ?: [] as $word) {
                    $length = mb_strwidth($word, 'UTF-8');
                    if ($used && $used + 1 + $length > 18) {
                        $lines++;
                        $used = 0;
                    }
                    $lines += max(0, (int) ceil($length / 18) - 1);
                    $used = $length > 18 ? (($length - 1) % 18) + 1 : $used + ($used ? 1 : 0) + $length;
                }
            }
            $maxLines = max($maxLines, $lines);
        }

        return min(409, $maxLines * 18 + 6);
    }

    private function row(XMLWriter $xml, int $number, array $values, bool $heading, array $statusColumns = [], array $centeredColumns = [], bool $autoRowHeight = false, array $dateColumns = [], bool $bandedRows = false): void
    {
        $xml->startElement('row');
        $xml->writeAttribute('r', (string) $number);
        if ($autoRowHeight) {
            $xml->writeAttribute('ht', (string) $this->rowHeight($values));
            $xml->writeAttribute('customHeight', '1');
        }
        // Bands preserve the base formats, including dates and colored icons.
        $bandOffset = $bandedRows && ! $heading ? ($number % 2 === 0 ? 13 : 26) : 0;
        foreach (array_values($values) as $index => $value) {
            $xml->startElement('c');
            $xml->writeAttribute('r', $this->column($index + 1).$number);
            if (! $heading && isset($dateColumns[$index])) {
                $xml->writeAttribute('s', (string) (12 + $bandOffset));
                if ($value !== null && $value !== '') {
                    $date = DateTimeImmutable::createFromFormat('!'.$dateColumns[$index], (string) $value);
                    if (! $date || $date->format($dateColumns[$index]) !== (string) $value) {
                        throw new RuntimeException('Date invalide dans l’export Excel.');
                    }
                    $xml->writeAttribute('t', 'n');
                    $xml->writeElement('v', (string) Date::PHPToExcel($date));
                }
                $xml->endElement();

                continue;
            }
            $xml->writeAttribute('t', 'inlineStr');
            $style = $heading ? '1' : '0';
            if (in_array($index, $centeredColumns, true)) {
                $style = $heading ? '5' : '4';
            }
            if (! $heading && in_array($index, $statusColumns, true)) {
                $style = match ($value) {
                    '✓' => '2',
                    '✗' => '3',
                    default => $style,
                };
            }
            $xml->writeAttribute('s', (string) ((int) $style + ($autoRowHeight ? 6 : 0) + $bandOffset));
            $xml->startElement('is');
            $xml->startElement('t');
            $xml->writeAttribute('xml:space', 'preserve');
            $xml->text(mb_substr(preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', (string) $value) ?? '', 0, 32767));
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();
        }
        $xml->endElement();
    }

    private function column(int $index): string
    {
        $name = '';
        while ($index > 0) {
            $index--;
            $name = chr(65 + $index % 26).$name;
            $index = intdiv($index, 26);
        }

        return $name;
    }
}
