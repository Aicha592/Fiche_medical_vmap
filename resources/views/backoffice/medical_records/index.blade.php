@extends('layouts.backoffice')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">Fiches médicales</h4>
            <div class="bo-muted">
                @if ($user->isMedecin())
                    Accès médical (données cliniques et avis).
                @else
                    Accès RH/QHSE (identification + QHSE).
                @endif
            </div>
        </div>
        <form class="gap-2 d-flex" method="GET" action="{{ route('backoffice.medical-records.index') }}">
            <input class="form-control" type="search" name="q" value="{{ $search }}"
                placeholder="Nom, prénom, matricule">
            <button class="btn btn-bo" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="bo-card">
        @if ($visits->isEmpty())
            <div class="bo-muted">Aucune fiche trouvée.</div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Agent</th>
                            <th>Matricule</th>
                            @if ($user->isDoctor())
                                <th>IMC</th>
                                <th>Avis</th>
                            @else
                                <th>Poste</th>
                                <th>QHSE (employé)</th>
                            @endif
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits as $visit)
                            @php
                                $qhse = $visit->employee?->qhse;
                                $qhseFields = [
                                    $qhse?->contrainte_manutention,
                                    $qhse?->contrainte_postures,
                                    $qhse?->nuisances_physiques,
                                    $qhse?->nuisances_chimiques,
                                    $qhse?->risques_mecaniques,
                                    $qhse?->organisation_travail,
                                    $qhse?->epi_disponibilite,
                                    $qhse?->epi_utilisation,
                                    $qhse?->epi_difficultes,
                                    $qhse?->formation_sst,
                                    $qhse?->appreciation_poste,
                                    $qhse?->observations_qhse,
                                    $qhse?->synthese_risque,
                                    $qhse?->synthese_facteurs,
                                    $qhse?->synthese_actions,
                                ];
                                $hasQhse = collect($qhseFields)
                                    ->filter(function ($value) {
                                        return !empty($value);
                                    })
                                    ->isNotEmpty();
                            @endphp
                            <tr>
                                <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                <td>{{ $visit->employee->nom ?? '' }} {{ $visit->employee->prenom ?? '' }}</td>
                                <td>{{ $visit->employee->matricule ?? '—' }}</td>
                                @if ($user->isDoctor())
                                    <td>{{ $visit->imc ?? '—' }}</td>
                                    <td>{{ $visit->avis ?? '—' }}</td>
                                @else
                                    <td>{{ $visit->employee->emploi_occupe ?? '—' }}</td>
                                    <td>
                                        <span class="bo-pill">{{ $hasQhse ? 'Complété (employé)' : 'En attente (employé)' }}</span>
                                    </td>
                                @endif
                                <td class="text-end">
                                    <a class="btn btn-outline-dark btn-sm"
                                        href="{{ route('backoffice.medical-records.show', $visit) }}">
                                        Consulter
                                    </a>
                                    @if ($user->isDoctor())
                                        <a class="btn btn-bo btn-sm" href="{{ route('medical-visits.pdf', $visit) }}"
                                            target="_blank">
                                            PDF
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
@endsection
