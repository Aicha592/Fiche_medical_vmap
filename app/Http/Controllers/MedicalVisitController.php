<?php

namespace App\Http\Controllers;

use App\Models\MedicalVisit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_doctor) {
                abort(403, 'Accès réservé au médecin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $recentVisits = MedicalVisit::with('user')
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
            'user_id' => 'required|exists:users,id',
            'taille' => 'required|numeric|min:50|max:250',
            'poids' => 'required|numeric|min:20|max:250',
            'tension' => 'nullable|string',
            'avis' => 'nullable|string',
        ]);

        $taille = $request->taille;
        $poids = $request->poids;

        $imc = $poids / (($taille / 100) * ($taille / 100));
        $imc = round($imc, 2);

        $visit = MedicalVisit::create([
            'user_id' => $request->user_id,
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

            // QHSE / SST
            'contrainte_manutention' => $request->qhse_manutention,
    'contrainte_postures' => $request->qhse_postures,

    'nuisances_physiques' => $request->qhse_nuisances_physiques,
    'nuisances_chimiques' => $request->qhse_nuisances_chimiques,

    'risques_mecaniques' => $request->qhse_risques,
    'organisation_travail' => $request->qhse_organisation,

    'epi_disponibilite' => $request->qhse_epi_dispo,
    'epi_utilisation' => $request->qhse_epi_utilisation,
    'epi_difficultes' => $request->qhse_epi_difficulte,

    'formation_sst' => $request->qhse_formation,
    'appreciation_poste' => $request->qhse_appreciation,
    'observations_qhse' => $request->qhse_observations,

    'synthese_risque' => $request->qhse_synthese_risque,
    'synthese_facteurs' => $request->qhse_synthese_facteurs,
    'synthese_actions' => $request->qhse_synthese_actions,
        ]);

        $this->storePdf($visit);

        if ($request->boolean('download_pdf')) {
            return $this->downloadPdf($visit);
        }

        return redirect()->back()->with('success', 'Visite médicale enregistrée avec succès');
    }

    public function pdf(MedicalVisit $medicalVisit)
    {
        $this->storePdf($medicalVisit);

        return $this->downloadPdf($medicalVisit);
    }

    private function downloadPdf(MedicalVisit $medicalVisit)
    {
        $medicalVisit->load('user');

        if ($medicalVisit->pdf_path && Storage::disk('local')->exists($medicalVisit->pdf_path)) {
            $filename = 'fiche-medicale-' . $medicalVisit->id . '.pdf';
            return Storage::disk('local')->download($medicalVisit->pdf_path, $filename);
        }

        $pdf = Pdf::loadView('medical_visits.pdf', [
            'visit' => $medicalVisit,
            'user' => $medicalVisit->user,
        ])->setPaper('a4', 'portrait');

        $filename = 'fiche-medicale-' . $medicalVisit->id . '.pdf';

        return $pdf->download($filename);
    }

    private function storePdf(MedicalVisit $medicalVisit): void
    {
        $medicalVisit->load('user');

        $pdf = Pdf::loadView('medical_visits.pdf', [
            'visit' => $medicalVisit,
            'user' => $medicalVisit->user,
        ])->setPaper('a4', 'portrait');

        $path = 'medical_visits/fiche-medicale-' . $medicalVisit->id . '.pdf';
        Storage::disk('local')->put($path, $pdf->output());

        if ($medicalVisit->pdf_path !== $path) {
            $medicalVisit->pdf_path = $path;
            $medicalVisit->save();
        }
    }
}
