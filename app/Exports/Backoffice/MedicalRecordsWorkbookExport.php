<?php

namespace App\Exports\Backoffice;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithProperties;

class MedicalRecordsWorkbookExport implements WithMultipleSheets, WithProperties
{
    public function __construct(
        private array $medicalHeaders,
        private array $medicalRows,
        private array $qhseHeaders,
        private array $qhseRows,
        private bool $includeMedicalSheet = true,
        private array $authenticity = []
    ) {
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->includeMedicalSheet) {
            $sheets[] = new MedicalRecordsSheetExport('Donnees medicales', $this->medicalHeaders, $this->medicalRows);
        }

        $sheets[] = new MedicalRecordsSheetExport('Donnees QHSE', $this->qhseHeaders, $this->qhseRows);
        $sheets[] = new MedicalRecordsAuthenticitySheetExport($this->authenticityMetadata());

        return $sheets;
    }

    public function properties(): array
    {
        $metadata = $this->authenticityMetadata();

        return [
            'creator' => 'VMAP - ' . $metadata['generated_by'],
            'lastModifiedBy' => 'VMAP',
            'title' => 'Export authentifié des fiches médicales',
            'description' => 'Export VMAP authentifié - Référence ' . $metadata['reference'],
            'subject' => 'Données médicales et QHSE',
            'keywords' => 'VMAP, médical, QHSE, authentifié',
        ];
    }

    private function authenticityMetadata(): array
    {
        $metadata = array_merge([
            'reference' => 'NON-RENSEIGNEE',
            'generated_at' => now()->toIso8601String(),
            'generated_by' => 'Utilisateur VMAP',
        ], $this->authenticity);

        $payload = [
            'reference' => $metadata['reference'],
            'generated_at' => $metadata['generated_at'],
            'generated_by' => $metadata['generated_by'],
            'medical_headers' => $this->medicalHeaders,
            'medical_rows' => $this->medicalRows,
            'qhse_headers' => $this->qhseHeaders,
            'qhse_rows' => $this->qhseRows,
        ];

        return array_merge($metadata, [
            'medical_rows' => count($this->medicalRows),
            'qhse_rows' => count($this->qhseRows),
            'signature' => hash_hmac(
                'sha256',
                json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                (string) config('app.key')
            ),
        ]);
    }
}
