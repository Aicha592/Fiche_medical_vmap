<?php

namespace Tests\Feature\Backoffice;

use App\Models\Employee;
use App\Models\MedicalVisit;
use App\Models\QhseEvaluation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardRegionCountsTest extends TestCase
{
  use RefreshDatabase;

  public function test_dashboard_counts_medical_visits_and_qhse_by_region(): void
  {
    $adminId = DB::table('users')->insertGetId([
      'name' => 'Admin Test',
      'email' => 'admin@example.com',
      'password' => bcrypt('secret'),
      'is_doctor' => false,
      'telephone' => '0600000000',
      'role' => 'admin',
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    $admin = User::find($adminId);

    $userIds = [];
    foreach (['R1A', 'R1B', 'R1C', 'R2A'] as $matricule) {
      $userIds[$matricule] = DB::table('users')->insertGetId([
        'name' => $matricule,
        'email' => strtolower($matricule) . '@example.com',
        'password' => bcrypt('secret'),
        'is_doctor' => false,
        'telephone' => '060000000' . (count($userIds) + 1),
        'role' => 'rh',
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    $employeeR1AId = DB::table('employees')->insertGetId([
      'user_id' => $userIds['R1A'],
      'matricule' => 'R1A',
      'nom' => 'Alpha',
      'prenom' => 'Alice',
      'delegation_r' => 'Région A',
      'date_passage' => '2026-01-10',
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    $employeeR1BId = DB::table('employees')->insertGetId([
      'user_id' => $userIds['R1B'],
      'matricule' => 'R1B',
      'nom' => 'Bravo',
      'prenom' => 'Bob',
      'delegation_r' => 'Région A',
      'date_passage' => '2026-01-11',
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    $employeeR1CId = DB::table('employees')->insertGetId([
      'user_id' => $userIds['R1C'],
      'matricule' => 'R1C',
      'nom' => 'Charlie',
      'prenom' => 'Celine',
      'delegation_r' => 'Région A',
      'date_passage' => '2026-01-12',
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    $employeeR2AId = DB::table('employees')->insertGetId([
      'user_id' => $userIds['R2A'],
      'matricule' => 'R2A',
      'nom' => 'Delta',
      'prenom' => 'Diane',
      'delegation_r' => 'Région B',
      'date_passage' => '2026-01-13',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    $employeeR1A = Employee::find($employeeR1AId);
    $employeeR1B = Employee::find($employeeR1BId);
    $employeeR1C = Employee::find($employeeR1CId);
    $employeeR2A = Employee::find($employeeR2AId);

    MedicalVisit::create(['employee_id' => $employeeR1A->id, 'avis' => 'Apte sans restriction', 'created_at' => '2026-01-10 09:00:00']);
    MedicalVisit::create(['employee_id' => $employeeR1B->id, 'avis' => 'Apte sans restriction', 'created_at' => '2026-01-10 09:05:00']);
    MedicalVisit::create(['employee_id' => $employeeR1C->id, 'avis' => 'Apte avec aménagement', 'created_at' => '2026-01-10 09:10:00']);
    MedicalVisit::create(['employee_id' => $employeeR2A->id, 'avis' => 'Inapte temporaire', 'created_at' => '2026-01-10 09:15:00']);
    MedicalVisit::create(['employee_id' => $employeeR1A->id, 'avis' => 'Apte sans restriction', 'created_at' => '2026-01-11 09:00:00']);

    QhseEvaluation::create([
      'employee_id' => $employeeR1A->id,
      'poste_occupe' => ['Agent de production'],
      'type_activite_dominante' => 'Terrain',
      'horaire_travail' => ['Jour'],
      'frequence_manutention' => 'Rare',
      'niveau_penibilite' => 3,
      'temoin_accident' => false,
      'niveau_risque_agent' => 'Modéré',
      'ameliorations_necessaires' => true,
    ]);
    QhseEvaluation::create([
      'employee_id' => $employeeR1B->id,
      'poste_occupe' => ['Agent de production'],
      'type_activite_dominante' => 'Mixte',
      'horaire_travail' => ['Jour'],
      'frequence_manutention' => 'Occasionnelle',
      'niveau_penibilite' => 2,
      'temoin_accident' => true,
      'niveau_risque_agent' => 'Élevé',
      'ameliorations_necessaires' => false,
    ]);
    QhseEvaluation::create([
      'employee_id' => $employeeR1A->id,
      'poste_occupe' => ['Agent de production'],
      'type_activite_dominante' => 'Terrain',
      'horaire_travail' => ['Jour'],
      'frequence_manutention' => 'Fréquente',
      'niveau_penibilite' => 4,
      'temoin_accident' => false,
      'niveau_risque_agent' => 'Élevé',
      'ameliorations_necessaires' => true,
    ]);
    QhseEvaluation::create([
      'employee_id' => $employeeR2A->id,
      'poste_occupe' => ['Logistique'],
      'type_activite_dominante' => 'Bureau',
      'horaire_travail' => ['Jour'],
      'frequence_manutention' => 'Rare',
      'niveau_penibilite' => 1,
      'temoin_accident' => false,
      'niveau_risque_agent' => 'Faible',
      'ameliorations_necessaires' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('backoffice.dashboard'));

    $response->assertOk();

    $regionCounts = $response->viewData('visitsByRegion')->keyBy('region');

    // Un employé ne compte qu'une fois, même s'il possède plusieurs visites
    // ou évaluations. Les totaux restent ainsi inférieurs ou égaux à l'effectif.
    $this->assertSame(3, $regionCounts['Région A']->done_total);
    $this->assertSame(2, $regionCounts['Région A']->qhse_total);
    $this->assertLessThanOrEqual($regionCounts['Région A']->region_total, $regionCounts['Région A']->done_total);
    $this->assertLessThanOrEqual($regionCounts['Région A']->region_total, $regionCounts['Région A']->qhse_total);
    $this->assertSame(1, $regionCounts['Région B']->done_total);
    $this->assertSame(1, $regionCounts['Région B']->qhse_total);
  }
}
