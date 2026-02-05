@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Fiches médicales</h4>
            <div class="bo-muted">
                @if($user->isDoctor())
                    Accès médical (données cliniques et avis).
                @else
                    Accès RH/QHSE (identification + QHSE).
                @endif
            </div>
        </div>
        <form class="d-flex gap-2" method="GET" action="{{ route('backoffice.medical-records.index') }}">
            <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Nom, prénom, matricule">
            <button class="btn btn-bo" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="bo-card">
        @if($visits->isEmpty())
            <div class="bo-muted">Aucune fiche trouvée.</div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Agent</th>
                            <th>Matricule</th>
                            @if($user->isDoctor())
                                <th>IMC</th>
                                <th>Avis</th>
                            @else
                                <th>Poste</th>
                                <th>QHSE</th>
                            @endif
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visits as $visit)
                            @php
                                $qhseFields = [
                                    $visit->contrainte_manutention,
                                    $visit->contrainte_postures,
                                    $visit->nuisances_physiques,
                                    $visit->nuisances_chimiques,
                                    $visit->risques_mecaniques,
                                    $visit->organisation_travail,
                                    $visit->epi_disponibilite,
                                    $visit->epi_utilisation,
                                    $visit->epi_difficultes,
                                    $visit->formation_sst,
                                    $visit->appreciation_poste,
                                    $visit->observations_qhse,
                                    $visit->synthese_risque,
                                    $visit->synthese_facteurs,
                                    $visit->synthese_actions,
                                ];
                                $hasQhse = collect($qhseFields)->filter(function ($value) {
                                    return !empty($value);
                                })->isNotEmpty();
                            @endphp
                            <tr>
                                <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                <td>{{ $visit->user->nom ?? '' }} {{ $visit->user->prenom ?? '' }}</td>
                                <td>{{ $visit->user->matricule ?? '—' }}</td>
                                @if($user->isDoctor())
                                    <td>{{ $visit->imc ?? '—' }}</td>
                                    <td>{{ $visit->avis ?? '—' }}</td>
                                @else
                                    <td>{{ $visit->user->poste ?? '—' }}</td>
                                    <td>
                                        <span class="bo-pill">{{ $hasQhse ? 'Complété' : 'En attente' }}</span>
                                    </td>
                                @endif
                                <td class="text-end">
                                    <a class="btn btn-outline-dark btn-sm" href="{{ route('backoffice.medical-records.show', $visit) }}">
                                        Consulter
                                    </a>
                                    @if($user->isDoctor())
                                        <a class="btn btn-bo btn-sm" href="{{ route('medical-visits.pdf', $visit) }}" target="_blank">
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
