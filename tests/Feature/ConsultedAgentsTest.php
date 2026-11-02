<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\BloodTest;
use App\Models\MedicalVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultedAgentsTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::forceCreate(['name' => 'Test', 'email' => uniqid().'@example.com',
            'password' => bcrypt('secret'), 'telephone' => uniqid(), 'role' => $role, 'is_doctor' => false]);
    }

    public function test_doctor_only_sees_their_consulted_agents_once_and_can_search(): void
    {
        $doctor = $this->user('doctor');
        $other = $this->user('doctor');
        $employee = Employee::create(['user_id' => $doctor->id, 'matricule' => 'ABC-15-001', 'prenom' => 'Awa', 'nom' => 'Ndiaye']);
        $excluded = Employee::create(['user_id' => $other->id, 'matricule' => 'OTHER-001', 'prenom' => 'Agent', 'nom' => 'Autre']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $doctor->id, 'created_at' => '2026-01-01']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $doctor->id, 'created_at' => '2026-02-01']);
        MedicalVisit::forceCreate(['employee_id' => $excluded->id, 'created_by_user_id' => $other->id, 'updated_by_user_id' => $doctor->id]);

        $this->actingAs($doctor)->get(route('medical-visits.consulted'))
            ->assertOk()->assertSee('Liste consultée')->assertSee('ABC-15-001')->assertDontSee('OTHER-001')
            ->assertSee('01/02/2026')->assertViewHas('employees', fn ($employees) => $employees->total() === 1);
        foreach (['Awa', 'Ndiaye', 'ABC-15'] as $search) {
            $this->get(route('medical-visits.consulted', ['q' => $search]))
                ->assertOk()->assertViewHas('employees', fn ($employees) => $employees->total() === 1);
        }
        $this->get(route('medical-visits.consulted', ['q' => 'Autre']))
            ->assertOk()->assertViewHas('employees', fn ($employees) => $employees->total() === 0);
        $this->actingAs($this->user('doctor'))->get(route('medical-visits.consulted'))
            ->assertOk()->assertSee('Aucun agent consulté');
    }

    public function test_detail_shows_own_latest_visit_and_latest_blood_test(): void
    {
        $doctor = $this->user('doctor');
        $employee = Employee::create(['user_id' => $doctor->id, 'matricule' => 'DETAIL-001', 'prenom' => 'Awa', 'nom' => 'Ndiaye']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $doctor->id,
            'observations' => 'Ancienne consultation', 'created_at' => '2026-01-01']);
        $visit = MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $doctor->id,
            'antecedents' => ['HTA'], 'observations' => 'Consultation récente', 'created_at' => '2026-02-01']);
        MedicalVisit::forceCreate(['employee_id' => $employee->id, 'created_by_user_id' => $this->user('doctor')->id,
            'observations' => 'Consultation autre docteur', 'created_at' => '2026-03-01']);
        BloodTest::forceCreate(['employee_id' => $employee->id, 'uree' => '0.2', 'created_at' => '2026-01-01']);
        $bloodTest = BloodTest::forceCreate(['employee_id' => $employee->id, 'uree' => '0.45', 'plt' => '250', 'created_at' => '2026-02-01']);

        $this->actingAs($doctor)->get(route('medical-visits.consulted'))
            ->assertOk()->assertSee('Action')->assertSee('Détail')
            ->assertSee(route('medical-visits.consulted.show', $employee));
        $this->get(route('medical-visits.consulted.show', $employee))
            ->assertOk()->assertSee('Consultation récente')->assertSee('HTA')->assertSee('0.45')->assertSee('250')
            ->assertDontSee('Ancienne consultation')->assertDontSee('Consultation autre docteur')
            ->assertViewHas('visit', fn ($value) => $value->id === $visit->id)
            ->assertViewHas('bloodTest', fn ($value) => $value->id === $bloodTest->id);
        $employee->bloodTests()->delete();
        $this->get(route('medical-visits.consulted.show', $employee))
            ->assertOk()->assertSee('Aucune analyse biologique enregistrée');
        $this->actingAs($this->user('doctor'))->get(route('medical-visits.consulted.show', $employee))->assertNotFound();
        $this->actingAs($this->user('bio'))->get(route('medical-visits.consulted.show', $employee))->assertForbidden();
    }

    public function test_consulted_list_requires_a_doctor(): void
    {
        $this->get(route('medical-visits.consulted'))->assertRedirect(route('login'));
        foreach (['rh', 'bio', 'admin', 'med-taf'] as $role) {
            $this->actingAs($this->user($role))->get(route('medical-visits.consulted'))->assertForbidden();
        }
    }
}
