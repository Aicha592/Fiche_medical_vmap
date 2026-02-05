<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                $normalized = $this->normalizeRow($row->toArray());

                $matricule = $normalized['matricule'] ?? null;
                $nom = $normalized['nom'] ?? null;
                $prenom = $normalized['prenom'] ?? null;

                if (!$matricule || !$nom || !$prenom) {
                    $this->skipped++;
                    $this->errors[] = "Ligne {$rowNumber}: matricule/nom/prenom manquant.";
                    continue;
                }

                $payload = [
                    'matricule' => $matricule,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'sexe' => $normalized['sexe'] ?? null,
                    'date_naissance' => $this->parseDate($normalized['date_naissance'] ?? null),
                    'date_embauche' => $this->parseDate($normalized['date_embauche'] ?? null),
                    'emploi_occupe' => $normalized['emploi_occupe'] ?? null,
                    'direction' => $normalized['direction'] ?? null,
                    'delegation_r' => $normalized['delegation_r'] ?? null,
                    'service' => $normalized['service'] ?? null,
                    'unite_communale' => $normalized['unite_communale'] ?? null,
                    'telephone' => $normalized['telephone'] ?? null,
                    'date_passage' => $this->parseDate($normalized['date_passage'] ?? null),
                    'site' => $normalized['site'] ?? null,
                ];

                $employee = Employee::where('matricule', $matricule)->first();

                if ($employee) {
                    $employee->update($payload);
                    $this->updated++;
                    continue;
                }

                Employee::create($payload);
                $this->created++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $key = $this->normalizeHeader($key);
            $normalized[$key] = is_string($value) ? trim($value) : $value;
        }

        return $normalized;
    }

    private function normalizeHeader(?string $key): string
    {
        $key = trim((string)$key);
        $key = mb_strtolower($key);
        $key = str_replace([' ', '-'], '_', $key);

        return match ($key) {
            'date_naissar', 'date_naiss' => 'date_naissance',
            'emploi_occup' => 'emploi_occupe',
            default => $key,
        };
    }

    private function parseDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format('Y-m-d');
        }

        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float)$value))->format('Y-m-d');
        }

        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                // try next
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
