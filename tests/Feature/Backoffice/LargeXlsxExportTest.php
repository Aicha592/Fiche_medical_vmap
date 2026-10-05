<?php

namespace Tests\Feature\Backoffice;

use App\Support\StreamingXlsxWriter;
use Tests\TestCase;
use XMLReader;

class LargeXlsxExportTest extends TestCase
{
    public function test_writer_exports_100000_rows_without_accumulating_cells_in_memory(): void
    {
        $baseline = memory_get_usage(true);
        $rows = (function () {
            for ($i = 0; $i < 100000; $i++) {
                yield [sprintf('%06d', $i), 'Agent é & <test>', '36', 'F', 'Emploi', 'Direction', 'Région', 'Service', 'Unité', 'NON', ''];
            }
        })();
        $path = (new StreamingXlsxWriter)->write([
            ['title' => 'DCH', 'headers' => array_fill(0, 11, 'COLONNE'), 'rows' => $rows],
        ]);
        try {
            $this->assertLessThan(32 * 1024 * 1024, memory_get_usage(true) - $baseline);
            $reader = new XMLReader;
            $this->assertTrue($reader->open('zip://'.$path.'#xl/worksheets/sheet1.xml'));
            $count = 0;
            while ($reader->read()) {
                if ($reader->nodeType === XMLReader::ELEMENT && $reader->localName === 'row') {
                    $count++;
                }
            }
            $reader->close();
            $this->assertSame(100001, $count);
        } finally {
            unlink($path);
        }
    }
}
