<?php

namespace App\Exports\Backoffice;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MedicalRecordsAuthenticitySheetExport implements FromArray, ShouldAutoSize, WithEvents, WithTitle
{
    public function __construct(private array $metadata)
    {
    }

    public function array(): array
    {
        return [
            ['AUTHENTIFICATION DU FICHIER EXPORTÉ', ''],
            ['Statut', 'Authentifié par l’application VMAP'],
            ['Référence unique', $this->metadata['reference']],
            ['Date de génération', $this->metadata['generated_at']],
            ['Généré par', $this->metadata['generated_by']],
            ['Nombre de lignes médicales', $this->metadata['medical_rows']],
            ['Nombre de lignes QHSE', $this->metadata['qhse_rows']],
            ['Algorithme', 'HMAC-SHA256'],
            ['Signature des données', $this->metadata['signature']],
            ['Note', 'Toute modification des données invalide cette signature applicative.'],
        ];
    }

    public function title(): string
    {
        return 'Authentification';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
                $sheet->mergeCells('A1:B1');
                $sheet->freezePane('A2');
                $sheet->getColumnDimension('B')->setWidth(80);
                $sheet->getStyle('A1:B10')->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 12],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                $sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 14,
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '356A45'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2:A10')->getFont()->setBold(true)->getColor()->setRGB('356A45');
            },
        ];
    }
}
