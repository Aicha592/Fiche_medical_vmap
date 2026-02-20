<?php

namespace App\Exports\Backoffice;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MedicalRecordsSheetExport implements FromArray, ShouldAutoSize, WithHeadings, WithTitle
{
    public function __construct(
        private string $title,
        private array $headers,
        private array $rows
    ) {
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title;
    }
}
