<?php

namespace App\Exports\Backoffice;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MedicalRecordsWorkbookExport implements WithMultipleSheets
{
    public function __construct(
        private array $medicalHeaders,
        private array $medicalRows,
        private array $qhseHeaders,
        private array $qhseRows,
        private bool $includeMedicalSheet = true
    ) {
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->includeMedicalSheet) {
            $sheets[] = new MedicalRecordsSheetExport('Donnees medicales', $this->medicalHeaders, $this->medicalRows);
        }

        $sheets[] = new MedicalRecordsSheetExport('Donnees QHSE', $this->qhseHeaders, $this->qhseRows);

        return $sheets;
    }
}
