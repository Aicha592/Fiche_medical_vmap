<?php

namespace App\Http\Controllers;

use App\Models\BloodTest;
use App\Models\MedicalVisit;
use App\Models\MedicalVisitQhse;
use App\Models\Resultat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicalVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->check() && (auth()->user()->isDoctor() || auth()->user()->isMedecin() || auth()->user()->isAdmin() || auth()->user()->isBio())) {
                return $next($request);
            }

            abort(403, 'Accès réservé au médecin ou au biologiste');
        });
    }

    public function index()
    {
        $recentVisits = MedicalVisit::with('employee')
            ->latest()
            ->take(10)
            ->get();

        return view('medical_visits.index', [
            'recentVisits' => $recentVisits,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'taille' => 'required|numeric|min:50|max:250',
            'poids' => 'required|numeric|min:20|max:250',
            'tension' => 'nullable|string',
            'avis' => 'nullable|string',
        ]);

        $taille = $request->taille;
        $poids = $request->poids;

        $imc = $poids / (($taille / 100) * ($taille / 100));
        $imc = round($imc, 2);

        $visit = MedicalVisit::where('employee_id', $request->employee_id)
            ->latest()
            ->first();

        if (!$visit) {
            $visit = new MedicalVisit([
                'employee_id' => $request->employee_id,
                'created_by_user_id' => $request->user()->id,
            ]);
        } elseif (!$visit->created_by_user_id) {
            $visit->created_by_user_id = $request->user()->id;
        }

        $visit->fill([
            'updated_by_user_id' => $request->user()->id,
            'antecedents' => $request->antecedents,
            'antecedents_precisions' => $request->antecedents_precisions,
            'taille' => $taille,
            'poids' => $poids,
            'imc' => $imc,
            'tension' => $request->tension,
            'stress' => $request->stress,
            'sommeil' => $request->sommeil,
            'charge_travail' => $request->charge_travail,
            'soutien' => $request->soutien,
            'avis' => $request->avis,
            'observations' => $request->observations,
        ]);
        $visit->save();
        $this->syncQhse($visit, $request);

        // $this->storePdf($visit);

        // if ($request->boolean('download_pdf')) {
        //     return $this->downloadPdf($visit);
        // }

        return redirect()->route('home')->with('success', 'Visite médicale enregistrée avec succès');
    }

    public function pdf(MedicalVisit $medicalVisit)
    {
        $this->storePdf($medicalVisit);

        return $this->downloadPdf($medicalVisit);
    }

    public function createBloodTest()
    {
        return view('medical_visits.blood_test');
    }

    public function storeBloodTest(Request $request)
    {
        // $request->validate([
        //     'file_name' => 'required|array',
        //     'file_name.*' => 'file|mimes:jpeg,png,pdf|max:2048',
        // ]);

        // $bloodTest = BloodTest::create([
        //     'employee_id' => $request->employee_id,
        //     'observations' => $request->observations,
        // ]);

        // // Handle file uploads (store on public disk so files can be served)
        // $saved = [];
        // foreach ($request->file('file_name') as $file) {
        //     $path = Storage::disk('public')->putFile('results', $file);

        //     Resultat::create([
        //         'blood_test_id' => $bloodTest->id,
        //         'file_path' => $path,
        //     ]);

        //     $saved[] = $path;
        // }

        // // Optionally store paths in session to show confirmation or later association
        // session()->flash('saved_files', $saved);

        //dd($request->employee_id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'uree' => 'required|numeric|decimal:0,5',
            'creat' => 'required|numeric|decimal:0,5',
            'asat' => 'required|numeric|decimal:0,5',
            'alat' => 'required|numeric|decimal:0,5',
            'aghbs' => 'required|in:Positif,Négatif',
            'chol' => 'required|numeric|decimal:0,5',
            'tg' => 'required|numeric|decimal:0,5',
            'gaj' => 'required|numeric|decimal:0,5',
            'hb' => 'required|numeric|decimal:0,5',
            'hct' => 'required|numeric|decimal:0,5',
            'gb' => 'required|numeric|decimal:0,5',
            'plt' => 'required|numeric|decimal:0,5',
        ], [
            'employee_id.required' => 'Vous devez sélectionner un agent.',
            'uree.required' => 'Le champ UREE est obligatoire.',
            'uree.numeric' => 'Le champ UREE doit être un nombre.',
            'uree.decimal' => 'Le champ UREE doit être un nombre avec jusqu\'à 5 décimales.',
            'creat.required' => 'Le champ CREAT est obligatoire.',
            'creat.numeric' => 'Le champ CREAT doit être un nombre.',
            'creat.decimal' => 'Le champ CREAT doit être un nombre avec jusqu\'à 5 décimales.',
            'asat.required' => 'Le champ ASAT est obligatoire.',
            'asat.numeric' => 'Le champ ASAT doit être un nombre.',
            'asat.decimal' => 'Le champ ASAT doit être un nombre avec jusqu\'à 5 décimales.',
            'alat.required' => 'Le champ ALAT est obligatoire.',
            'alat.numeric' => 'Le champ ALAT doit être un nombre.',
            'alat.decimal' => 'Le champ ALAT doit être un nombre avec jusqu\'à 5 décimales.',
            'aghbs.required' => 'Le champ AGHBS est obligatoire.',
            'chol.required' => 'Le champ CHOL TOT est obligatoire.',
            'chol.numeric' => 'Le champ CHOL TOT doit être un nombre.',
            'chol.decimal' => 'Le champ CHOL TOT doit être un nombre avec jusqu\'à 5 décimales.',
            'tg.required' => 'Le champ TG est obligatoire.',
            'tg.numeric' => 'Le champ TG doit être un nombre.',
            'tg.decimal' => 'Le champ TG doit être un nombre avec jusqu\'à 5 décimales.',
            'gaj.required' => 'Le champ GAJ est obligatoire.',
            'gaj.numeric' => 'Le champ GAJ doit être un nombre.',
            'gaj.decimal' => 'Le champ GAJ doit être un nombre avec jusqu\'à 5 décimales.',
            'hb.required' => 'Le champ HB est obligatoire.',
            'hb.numeric' => 'Le champ HB doit être un nombre.',
            'hb.decimal' => 'Le champ HB doit être un nombre avec jusqu\'à 5 décimales.',
            'hct.required' => 'Le champ HCT est obligatoire.',
            'hct.numeric' => 'Le champ HCT doit être un nombre.',
            'hct.decimal' => 'Le champ HCT doit être un nombre avec jusqu\'à 5 décimales.',
            'gb.required' => 'Le champ GB est obligatoire.',
            'gb.numeric' => 'Le champ GB doit être un nombre.',
            'gb.decimal' => 'Le champ GB doit être un nombre avec jusqu\'à 5 décimales.',
            'plt.required' => 'Le champ PLT est obligatoire.',
            'plt.numeric' => 'Le champ PLT doit être un nombre.',
            'plt.decimal' => 'Le champ PLT doit être un nombre avec jusqu\'à 5 décimales.',
        ]);

        $bloodTest = BloodTest::create([
            'employee_id' => $request->employee_id,
            'uree' => $request->uree,
            'creat' => $request->creat,
            'asat' => $request->asat,
            'alat' => $request->alat,
            'aghbs' => $request->aghbs,
            'chol' => $request->chol,
            'tg' => $request->tg,
            'gaj' => $request->gaj,
            'hb' => $request->hb,
            'hct' => $request->hct,
            'gb' => $request->gb,
            'plt' => $request->plt,
        ]);

        // $saved = [];
        // if ($request->hasFile('file_name')) {
        //     foreach ($request->file('file_name') as $file) {
        //         $path = Storage::disk('public')->putFile('results', $file);

        //         $bloodTest->results()->create([
        //             'file_path' => $path,
        //         ]);

        //         $saved[] = $path;
        //     }
        // }

        // if (!empty($saved)) {
        //     session()->flash('saved_files', $saved);
        // }

        return redirect()->route('medical-visits.blood_test_form')
            ->with('success', 'Bilan sanguin enregistré avec succès');
    }

    private function downloadPdf(MedicalVisit $medicalVisit)
    {
        $medicalVisit->load('employee', 'employee.qhse', 'employee.bloodTests', 'createdBy');

        if ($medicalVisit->pdf_path && Storage::disk('local')->exists($medicalVisit->pdf_path)) {
            $filename = $this->pdfFilename($medicalVisit);
            return Storage::disk('local')->download($medicalVisit->pdf_path, $filename);
        }

        $pdf = Pdf::loadView('medical_visits.pdf', [
            'visit' => $medicalVisit,
            'employee' => $medicalVisit->employee,
            'bloodTests' => $medicalVisit->employee?->bloodTests ?? collect(),
        ])->setPaper('a4', 'landscape');

        $filename = $this->pdfFilename($medicalVisit);

        return $pdf->download($filename);
    }

    private function pdfFilename(MedicalVisit $medicalVisit): string
    {
        $employee = $medicalVisit->employee;
        $identity = Str::upper(trim(implode(' ', array_filter([
            $employee?->prenom,
            $employee?->nom,
            $employee?->matricule,
        ]))));

        $identitySlug = Str::upper(Str::slug($identity, '-'));

        return 'Fiche-medicale-' . ($identitySlug ?: $medicalVisit->id) . '.pdf';
    }

    private function storePdf(MedicalVisit $medicalVisit): void
    {
        $medicalVisit->load('employee', 'employee.qhse', 'employee.bloodTests', 'createdBy');

        $pdf = Pdf::loadView('medical_visits.pdf', [
            'visit' => $medicalVisit,
            'employee' => $medicalVisit->employee,
            'bloodTests' => $medicalVisit->employee?->bloodTests ?? collect(),
        ])->setPaper('a4', 'landscape');

        $path = 'medical_visits/fiche-medicale-' . $medicalVisit->id . '.pdf';
        Storage::disk('local')->put($path, $pdf->output());

        if ($medicalVisit->pdf_path !== $path) {
            $medicalVisit->pdf_path = $path;
            $medicalVisit->save();
        }
    }

    private function syncQhse(MedicalVisit $visit, Request $request): void
    {
        if (!$visit->employee_id) {
            return;
        }

        $data = [
            'contrainte_manutention' => $request->qhse_manutention,
            'manutention_frequence' => $request->qhse_manutention_frequence,
            'manutention_precision' => $request->qhse_manutention_precision,
            'contrainte_postures' => $request->qhse_postures,
            'postures_penibilite' => $request->qhse_postures_penibilite,
            'nuisances_physiques' => $request->qhse_nuisances_physiques,
            'nuisances_chimiques' => $request->qhse_nuisances_chimiques,
            'risques_mecaniques' => $request->qhse_risques,
            'organisation_travail' => $request->qhse_organisation,
            'epi_disponibilite' => $request->qhse_epi_dispo,
            'epi_utilisation' => $request->qhse_epi_utilisation,
            'epi_difficultes' => $request->qhse_epi_difficulte,
            'epi_autres' => $request->qhse_epi_autres,
            'formation_sst' => $request->qhse_formation,
            'appreciation_poste' => $request->qhse_appreciation,
            'observations_qhse' => $request->qhse_observations,
            'synthese_risque' => $request->qhse_synthese_risque,
            'synthese_facteurs' => $request->qhse_synthese_facteurs,
            'synthese_actions' => $request->qhse_synthese_actions,
        ];

        MedicalVisitQhse::updateOrCreate(
            ['employee_id' => $visit->employee_id],
            $data
        );
    }
}
