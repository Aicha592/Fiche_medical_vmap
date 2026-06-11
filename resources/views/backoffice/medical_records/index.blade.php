@extends('layouts.backoffice')

@section('content')
    <style>
        .biology-value {
            background: rgba(177, 197, 106, 0.16);
            border: 1px solid rgba(53, 106, 69, 0.22);
            border-radius: 12px;
            padding: 12px;
            height: 100%;
        }

        .biology-value strong {
            color: var(--bo-accent);
            display: block;
            font-size: 1.1rem;
            margin-top: 4px;
        }

        .biology-modal-header {
            background: var(--bo-accent);
        }
    </style>

    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">Fiches médicales</h4>
            <div class="bo-muted">
                @if ($user->isMedecin() || $user->isAdmin())
                    Accès médical (données cliniques et avis).
                @else
                    Accès RH/QHSE (identification + QHSE).
                @endif
            </div>
        </div>
        <div class="gap-2 d-flex">
            <a class="btn btn-outline-dark" href="{{ route('backoffice.medical-records.export', ['q' => $search]) }}">
                Export Excel
            </a>
            <form class="gap-2 d-flex" method="GET" action="{{ route('backoffice.medical-records.index') }}">
                <input class="form-control" type="search" name="q" value="{{ $search }}"
                    placeholder="Nom, prénom, matricule">
                <button class="btn btn-bo" type="submit">Rechercher</button>
            </form>
        </div>
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
                            @if ($user->isMedecin() || $user->isAdmin())
                                <th>IMC</th>
                                <th>Avis</th>
                                <th>Résultat Biologie</th>
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
                                $employeeId = $visit->employee_id;
                                $bloodTests = $bloodTestsByEmployee->get($employeeId, collect());
                            @endphp
                            <tr>
                                <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                <td>{{ $visit->employee->nom ?? '' }} {{ $visit->employee->prenom ?? '' }}</td>
                                <td>{{ $visit->employee->matricule ?? '—' }}</td>
                                @if ($user->isMedecin() || $user->isAdmin())
                                    <td>{{ $visit->imc ?? '—' }}</td>
                                    <td>{{ $visit->avis ?? '—' }}</td>
                                    <td>
                                        @if ($bloodTests->isNotEmpty())
                                            <button class="btn btn-outline-success btn-sm text-nowrap" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#biologyModalEmployee{{ $employeeId }}">
                                                Voir les résultats
                                            </button>
                                        @else
                                            <span class="bo-muted">Non disponible</span>
                                        @endif
                                    </td>
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
                                    @if ($user->isMedecin())
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

            @if ($user->isMedecin() || $user->isAdmin())
                @php
                    $bloodTestFields = [
                        'uree' => 'URÉE (g/L)',
                        'creat' => 'CRÉAT (mg/L)',
                        'asat' => 'ASAT (UI/L)',
                        'alat' => 'ALAT (UI/L)',
                        'aghbs' => 'AGHBS',
                        'chol' => 'CHOL TOT (g/L)',
                        'tg' => 'TG (g/L)',
                        'gaj' => 'GAJ (g/L)',
                        'hb' => 'HB (g/dl)',
                        'hct' => 'HCT (%)',
                        'gb' => 'GB (g/L (10^9/L))',
                        'plt' => 'PLT (g/L (10^9/L))',
                    ];
                @endphp

                @foreach ($visits->getCollection()->unique('employee_id') as $visit)
                    @php
                        $employeeId = $visit->employee_id;
                        $bloodTests = $bloodTestsByEmployee->get($employeeId, collect());
                    @endphp
                    @if ($bloodTests->isNotEmpty())
                        <div class="modal fade" id="biologyModalEmployee{{ $employeeId }}" tabindex="-1"
                            aria-labelledby="biologyModalLabelEmployee{{ $employeeId }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="text-white modal-header biology-modal-header">
                                        <div>
                                            <h5 class="mb-1 modal-title"
                                                id="biologyModalLabelEmployee{{ $employeeId }}">
                                                Résultats biologiques
                                            </h5>
                                            <div>
                                                {{ $visit->employee->nom ?? '' }}
                                                {{ $visit->employee->prenom ?? '' }}
                                                · {{ $visit->employee->matricule ?? '—' }}
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach ($bloodTests as $bloodTest)
                                            <section class="p-3 mb-3 border rounded-3">
                                                <div class="mb-3 fw-semibold">
                                                    Bilan enregistré le {{ $bloodTest->created_at->format('d/m/Y à H:i') }}
                                                </div>
                                                <div class="row g-3">
                                                    @foreach ($bloodTestFields as $field => $label)
                                                        <div class="col-sm-6 col-lg-3">
                                                            <div class="biology-value">
                                                                <span class="bo-muted">{{ $label }}</span>
                                                                <strong>{{ filled($bloodTest->{$field}) ? $bloodTest->{$field} : '—' }}</strong>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </section>
                                        @endforeach
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-dark"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <div class="mt-3">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
@endsection
