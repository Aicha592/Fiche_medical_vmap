<?php

namespace Tests\Feature\Backoffice;

use App\Models\Employee;
use App\Models\BloodTest;
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
        $other->update(['name' => 'Auteur de la visite']);
        Employee::create(['user_id' => $other->id, 'matricule' => '00456', 'nom' => 'Sans visite', 'prenom' => 'Agent']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $admin->id, 'created_at' => '2026-01-01 10:00:00']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $other->id, 'created_at' => '2026-02-01 10:00:00']);

        $response = $this->actingAs($admin)->get(route('backoffice.medical-records.export', ['format' => 'dch']));
        $response->assertOk();
        $path = $response->baseResponse->getFile()->getPathname();
        try {
            $book = IOFactory::load($path);
            $rows = $book->getSheet(0)->toArray();
            $this->assertCount(3, $rows);
            $this->assertSame(['MATRICULE', 'AGENT', 'AGE', 'SEXE', 'EMPLOI OCCUPE', 'DIRECTION', 'DELEGATION REGIONALE', 'DELEGATION DEPARTEMENTALE / SERVICE', 'UNITE COMMUNALE', 'VISITE MEDICALE', 'DATE VISITE', 'VISITE EFFECTUEE PAR'], $rows[0]);
            foreach (range('A', 'L') as $column) {
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
            $this->assertSame('Auteur de la visite', $rows[1][11]);
            $this->assertSame('n', $book->getSheet(0)->getCell('K2')->getDataType());
            $this->assertSame('2026-02-01', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($book->getSheet(0)->getCell('K2')->getValue())->format('Y-m-d'));
            foreach (range('A', 'L') as $column) {
                foreach ([1, 2, 3] as $row) {
                    $this->assertSame('center', $book->getSheet(0)->getStyle($column.$row)->getAlignment()->getVertical());
                }
            }
            $this->assertSame('✗', $rows[2][9]);
            $this->assertNull($rows[2][10]);
            $this->assertSame('', $rows[2][11]);
            $this->assertSame('FF008000', $book->getSheet(0)->getStyle('J2')->getFont()->getColor()->getARGB());
            $this->assertSame('FFFF0000', $book->getSheet(0)->getStyle('J3')->getFont()->getColor()->getARGB());
            $this->assertSame('hidden', $book->getSheet(1)->getSheetState());
            $book->disconnectWorksheets();
        } finally {
            unlink($path);
        }
    }

    public function test_dch_leaves_author_empty_when_latest_visit_has_no_author(): void
    {
        $admin = $this->user('admin');
        $employee = Employee::create(['user_id' => $admin->id, 'matricule' => '00789', 'nom' => 'Agent', 'prenom' => 'Test']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $admin->id, 'created_at' => '2026-01-01 10:00:00']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_at' => '2026-02-01 10:00:00']);

        $response = $this->actingAs($admin)->get(route('backoffice.medical-records.export', ['format' => 'dch']));
        $response->assertOk();
        $path = $response->baseResponse->getFile()->getPathname();
        try {
            $book = IOFactory::load($path);
            $this->assertSame('✓', $book->getSheet(0)->getCell('J2')->getFormattedValue());
            $this->assertSame('', $book->getSheet(0)->getCell('L2')->getFormattedValue());
            $book->disconnectWorksheets();
        } finally {
            unlink($path);
        }
    }

    public function test_dch_with_record_appends_latest_medical_and_lab_data(): void
    {
        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-11-02'));
        $admin = $this->user('admin');
        $employee = Employee::create(['user_id' => $admin->id, 'matricule' => 'ABC-15-00123', 'date_embauche' => '2020-01-01', 'nom' => 'Agent', 'prenom' => 'Test']);
        $withoutVisit = Employee::create(['user_id' => $this->user('rh')->id, 'matricule' => 'ABC-00-00456', 'nom' => 'Sans visite', 'prenom' => 'Agent']);
        Employee::create(['user_id' => $this->user('rh')->id, 'matricule' => '00789', 'nom' => 'Sans données', 'prenom' => 'Agent']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'observations' => 'Ancienne fiche', 'created_at' => '2026-01-01']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $admin->id,
            'observations' => 'Dernière fiche', 'taille' => '175', 'created_at' => '2026-02-01']);
        BloodTest::forceCreate(['employee_id' => $employee->id, 'uree' => '0.2', 'created_at' => '2026-01-01']);
        BloodTest::forceCreate(['employee_id' => $employee->id, 'uree' => '0.4', 'plt' => '250', 'created_at' => '2026-02-01']);
        BloodTest::forceCreate(['employee_id' => $withoutVisit->id, 'uree' => '0.3', 'created_at' => '2026-02-01']);

        $response = $this->actingAs($admin)->get(route('backoffice.medical-records.export', ['format' => 'dch_with_record']));
        $response->assertOk();
        $path = $response->baseResponse->getFile()->getPathname();
        try {
            $book = IOFactory::load($path);
            $sheet = $book->getSheet(0);
            $rows = $sheet->toArray();
            $this->assertSame('DCH avec fiche', $sheet->getTitle());
            $this->assertCount(4, $rows);
            $this->assertCount(38, $rows[0]);
            $first = array_combine($rows[0], $rows[1]);
            $second = array_combine($rows[0], $rows[2]);
            $third = array_combine($rows[0], $rows[3]);
            $this->assertSame('ABC-15-00123', $first['MATRICULE']);
            $this->assertSame('11', $first['ANCIENNETE (ANS)']);
            $this->assertSame('26', $second['ANCIENNETE (ANS)']);
            $this->assertSame('', $third['ANCIENNETE (ANS)']);
            $this->assertSame('01/02/2026', $first['DATE VISITE']);
            $this->assertSame($admin->name, $first['VISITE EFFECTUEE PAR']);
            $this->assertSame('Dernière fiche', $first['OBSERVATIONS']);
            $this->assertSame('175', $first['TAILLE']);
            $this->assertSame('0.4', $first['UREE (G/L)']);
            $this->assertSame('250', $first['PLT (10^9/L)']);
            $this->assertSame('✗', $second['VISITE MEDICALE']);
            $this->assertSame('', $second['OBSERVATIONS']);
            $this->assertSame('0.3', $second['UREE (G/L)']);
            $this->assertSame('', $third['UREE (G/L)']);
            $this->assertSame('FF356A45', $sheet->getStyle('AL1')->getFill()->getStartColor()->getARGB());
            $book->disconnectWorksheets();
        } finally {
            unlink($path);
        }
    }

    public function test_dch_with_record_requires_medical_access(): void
    {
        $this->actingAs($this->user('ch'))
            ->get(route('backoffice.medical-records.export', ['format' => 'dch_with_record']))
            ->assertForbidden();
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
