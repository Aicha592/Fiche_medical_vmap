@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Tableau de bord</h4>
            <div class="bo-muted">Vue d’ensemble des visites et des accès.</div>
        </div>
        @if($user->isDoctor() || $user->isRh())
            <a class="btn btn-bo" href="{{ route('backoffice.medical-records.index') }}">Consulter les fiches</a>
        @endif
    </div>

    <div class="row g-3 mb-4">
        @if($user->isDoctor() || $user->isRh())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Total visites</div>
                    <div class="fs-3 fw-semibold">{{ $stats['total_visits'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">7 derniers jours</div>
                    <div class="fs-3 fw-semibold">{{ $stats['visits_last_7_days'] }}</div>
                </div>
            </div>
        @endif
        @if($user->isRh())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Fiches avec QHSE</div>
                    <div class="fs-3 fw-semibold">{{ $stats['qhse_filled'] }}</div>
                </div>
            </div>
        @endif
        @if($user->isAdmin())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Utilisateurs</div>
                    <div class="fs-3 fw-semibold">{{ $stats['users_count'] }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="bo-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="fw-semibold">Dernières visites</div>
            @if($user->isDoctor() || $user->isRh())
                <a class="btn btn-outline-dark btn-sm" href="{{ route('backoffice.medical-records.index') }}">Voir tout</a>
            @endif
        </div>
        @if($recentVisits->isEmpty())
            <div class="bo-muted">Aucune visite enregistrée.</div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Agent</th>
                            <th>Matricule</th>
                            @if($user->isDoctor())
                                <th>Avis</th>
                            @endif
                            @if($user->isRh())
                                <th>QHSE</th>
                            @endif
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentVisits as $visit)
                            <tr>
                                <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                <td>{{ $visit->user->nom ?? '' }} {{ $visit->user->prenom ?? '' }}</td>
                                <td>{{ $visit->user->matricule ?? '—' }}</td>
                                @if($user->isDoctor())
                                    <td>{{ $visit->avis ?? '—' }}</td>
                                @endif
                                @if($user->isRh())
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
                                    <td>
                                        <span class="bo-pill">{{ $hasQhse ? 'Complété' : 'En attente' }}</span>
                                    </td>
                                @endif
                                <td class="text-end">
                                    @if($user->isDoctor() || $user->isRh())
                                        <a class="btn btn-outline-dark btn-sm"
                                           href="{{ route('backoffice.medical-records.show', $visit) }}">
                                            Ouvrir
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
