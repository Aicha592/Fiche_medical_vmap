<?php

namespace Tests\Feature\Backoffice;

use App\Models\Employee;
use App\Models\MedicalVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class MedicalRecordExportTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::forceCreate(['name' => 'Export Test', 'email' => uniqid().'@example.com',
            'password' => bcrypt('secret'), 'telephone' => uniqid(), 'role' => $role, 'is_doctor' => false]);
    }

    public function test_dch_exports_all_employees_once_with_the_latest_visit(): void
    {
        $admin = $this->user('admin');
        $employee = Employee::create(['user_id' => $admin->id, 'matricule' => '00123', 'nom' => 'Ndiaye', 'prenom' => 'Aïssatou', 'date_naissance' => '1990-01-01', 'sexe' => 'F']);
        $other = $this->user('rh');
        Employee::create(['user_id' => $other->id, 'matricule' => '00456', 'nom' => 'Sans visite', 'prenom' => 'Agent']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_at' => '2026-01-01 10:00:00']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_at' => '2026-02-01 10:00:00']);

        $response = $this->actingAs($admin)->get(route('backoffice.medical-records.export', ['format' => 'dch']));
        $response->assertOk();
        $path = $response->baseResponse->getFile()->getPathname();
        try {
            $book = IOFactory::load($path);
            $rows = $book->getSheet(0)->toArray();
            $this->assertCount(3, $rows);
            $this->assertSame(['MATRICULE', 'AGENT', 'AGE', 'SEXE', 'EMPLOI OCCUPE', 'DIRECTION', 'DELEGATION REGIONALE', 'DELEGATION DEPARTEMENTALE / SERVICE', 'UNITE COMMUNALE', 'VISITE MEDICALE', 'DATE VISITE'], $rows[0]);
            foreach (range('A', 'K') as $column) {
                $sheet = $book->getSheet(0);
                $this->assertEquals(24, $sheet->getColumnDimension($column)->getWidth());
                $this->assertSame('FF356A45', $sheet->getStyle($column.'1')->getFill()->getStartColor()->getARGB());
                $this->assertSame('FFE2F0D9', $sheet->getStyle($column.'2')->getFill()->getStartColor()->getARGB());
                $this->assertSame('FFFFFFFF', $sheet->getStyle($column.'3')->getFill()->getStartColor()->getARGB());
            }
            $this->assertGreaterThan(24, $book->getSheet(0)->getRowDimension(1)->getRowHeight());
            $this->assertTrue($book->getSheet(0)->getStyle('B2')->getAlignment()->getWrapText());
            $this->assertSame('center', $book->getSheet(0)->getStyle('J2')->getAlignment()->getHorizontal());
            $this->assertSame('00123', $rows[1][0]);
            $this->assertSame('Aïssatou Ndiaye', $rows[1][1]);
            $this->assertSame('✓', $rows[1][9]);
            $this->assertSame('01/02/2026', $rows[1][10]);
            $this->assertSame('n', $book->getSheet(0)->getCell('K2')->getDataType());
            $this->assertSame('2026-02-01', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($book->getSheet(0)->getCell('K2')->getValue())->format('Y-m-d'));
            foreach (range('A', 'K') as $column) {
                foreach ([1, 2, 3] as $row) {
                    $this->assertSame('center', $book->getSheet(0)->getStyle($column.$row)->getAlignment()->getVertical());
                }
            }
            $this->assertSame('✗', $rows[2][9]);
            $this->assertNull($rows[2][10]);
            $this->assertSame('FF008000', $book->getSheet(0)->getStyle('J2')->getFont()->getColor()->getARGB());
            $this->assertSame('FFFF0000', $book->getSheet(0)->getStyle('J3')->getFont()->getColor()->getARGB());
            $this->assertSame('hidden', $book->getSheet(1)->getSheetState());
            $book->disconnectWorksheets();
        } finally {
            unlink($path);
        }
    }

    public function test_medical_export_preserves_sheets_and_restricts_clinical_data(): void
    {
        foreach (['admin' => ['Donnees medicales', 'Donnees QHSE', 'Authentification'], 'ch' => ['Donnees QHSE', 'Authentification']] as $role => $titles) {
            $user = $this->user($role);
            $employee = Employee::create(['user_id' => $user->id, 'matricule' => 'TEST-'.$role, 'nom' => 'Agent', 'prenom' => 'Test']);
            MedicalVisit::create(['employee_id' => $employee->id, 'observations' => '=Donnée clinique']);
            $response = $this->actingAs($user)->get(route('backoffice.medical-records.export'));
            $response->assertOk();
            $path = $response->baseResponse->getFile()->getPathname();
            try {
                $book = IOFactory::load($path);
                $this->assertSame($titles, $book->getSheetNames());
                $this->assertSame('TEST-'.$role, $book->getSheet(0)->getCell('A2')->getFormattedValue());
                if ($role === 'admin') {
                    $this->assertSame('=Donnée clinique', $book->getSheet(0)->getCell('V2')->getFormattedValue());
                    $this->assertNotSame('f', $book->getSheet(0)->getCell('V2')->getDataType());
                }
                $book->disconnectWorksheets();
            } finally {
                unlink($path);
            }
        }
    }

    public function test_dch_search_includes_matching_employees_without_visits(): void
    {
        $admin = $this->user('admin');
        Employee::create(['user_id' => $admin->id, 'matricule' => '00999', 'nom' => 'Sans visite', 'prenom' => 'Agent']);
        $response = $this->actingAs($admin)->get(route('backoffice.medical-records.export', ['format' => 'dch', 'q' => '00999']));
        $response->assertOk();
        $path = $response->baseResponse->getFile()->getPathname();
        try {
            $book = IOFactory::load($path);
            $this->assertSame('00999', $book->getSheet(0)->getCell('A2')->getFormattedValue());
            $this->assertSame('✗', $book->getSheet(0)->getCell('J2')->getFormattedValue());
            $book->disconnectWorksheets();
        } finally {
            unlink($path);
        }
    }
}
