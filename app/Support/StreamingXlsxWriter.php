<?php

namespace App\Support;

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
                $counts[$sheet['title']] = $this->worksheet($xmlPath, $sheet['headers'], $sheet['rows'], $hash);
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
            $zip->addFromString('xl/styles.xml', '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="12"/><name val="Arial"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="12"/><name val="Arial"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF356A45"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="1"><border/></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="2"><xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="49" fontId="1" fillId="2" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" vertical="center"/></xf></cellXfs></styleSheet>');
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

    private function worksheet(string $path, array $headers, iterable $rows, $hash = null): int
    {
        $xml = new XMLWriter;
        if (! $xml->openUri($path)) {
            throw new RuntimeException('Impossible de créer la feuille Excel.');
        }
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('worksheet');
        $xml->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $xml->writeRaw('<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews><cols><col min="1" max="'.max(count($headers), 2).'" width="24" customWidth="1"/></cols><sheetData>');
        $number = 0;
        if ($hash) {
            hash_update($hash, json_encode($headers, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        }
        if ($headers) {
            $this->row($xml, ++$number, $headers, true);
        }
        $count = 0;
        foreach ($rows as $row) {
            if ($hash) {
                hash_update($hash, json_encode($row, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
            }
            $this->row($xml, ++$number, $row, false);
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

    private function row(XMLWriter $xml, int $number, array $values, bool $heading): void
    {
        $xml->startElement('row');
        $xml->writeAttribute('r', (string) $number);
        foreach (array_values($values) as $index => $value) {
            $xml->startElement('c');
            $xml->writeAttribute('r', $this->column($index + 1).$number);
            $xml->writeAttribute('t', 'inlineStr');
            $xml->writeAttribute('s', $heading ? '1' : '0');
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
