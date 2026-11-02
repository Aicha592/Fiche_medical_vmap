@extends('layouts.app')

@section('content')
    <div class="gap-3 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3>Détail de la consultation</h3>
            <p class="mb-0 text-muted">{{ $employee->prenom }} {{ $employee->nom }} — {{ $employee->matricule }}</p>
        </div>
        <a class="btn btn-outline-success" href="{{ route('medical-visits.consulted') }}">Retour à la liste</a>
    </div>

    <section class="mb-4 card">
        <div class="card-body">
            <h4 class="card-title">Fiche médicale</h4>
            <p>Date visite : {{ $visit->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
            <dl class="row">
                @foreach (['antecedents' => 'Antécédents', 'antecedents_precisions' => 'Précisions sur les antécédents',
                    'taille' => 'Taille (cm)', 'poids' => 'Poids (kg)', 'imc' => 'IMC', 'tension' => 'Tension',
                    'stress' => 'Stress', 'sommeil' => 'Sommeil', 'charge_travail' => 'Charge de travail',
                    'soutien' => 'Soutien', 'avis' => 'Avis médical', 'observations' => 'Observations'] as $field => $label)
                    <dt class="col-sm-4">{{ $label }}</dt>
                    <dd class="col-sm-8" style="white-space: pre-line">{{ is_array($visit->$field) ? (implode(', ', $visit->$field) ?: '—') : ($visit->$field ?? '—') }}</dd>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="mb-4 card">
        <div class="card-body">
            <h4 class="card-title">Dernière analyse biologique</h4>
            @if ($bloodTest)
                <p>Date du bilan : {{ $bloodTest->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>Analyse</th><th>Résultat</th></tr></thead>
                        <tbody>
                            @foreach (['uree' => 'Urée (g/L)', 'creat' => 'Créatinine (mg/L)', 'asat' => 'ASAT (UI/L)',
                                'alat' => 'ALAT (UI/L)', 'aghbs' => 'AgHBs', 'chol' => 'Cholestérol total (g/L)',
                                'tg' => 'Triglycérides (g/L)', 'gaj' => 'Glycémie à jeun (g/L)', 'hb' => 'Hémoglobine (g/dL)',
                                'hct' => 'Hématocrite (%)', 'gb' => 'Globules blancs (10^9/L)', 'plt' => 'Plaquettes (10^9/L)'] as $field => $label)
                                <tr><td>{{ $label }}</td><td>{{ $bloodTest->$field ?? '—' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mb-0 text-muted">Aucune analyse biologique enregistrée pour cet agent.</p>
            @endif
        </div>
    </section>
@endsection
