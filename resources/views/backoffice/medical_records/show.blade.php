@extends('layouts.backoffice')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">Fiche médicale</h4>
            <div class="bo-muted">Consultation en lecture seule.</div>
        </div>
        <div class="gap-2 d-flex">
            <a class="btn btn-outline-dark" href="{{ route('backoffice.medical-records.index') }}">Retour</a>
            @if ($user->isDoctor())
                <a class="btn btn-bo" href="{{ route('medical-visits.pdf', $visit) }}" target="_blank">Télécharger PDF</a>
            @endif
        </div>
    </div>

    <div class="mb-4 row g-3">
        <div class="col-lg-5">
            <div class="bo-card h-100">
                <div class="mb-2 fw-semibold">Identification</div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="bo-muted">Nom</div>
                        <div>{{ $visit->employee->nom ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Prénom</div>
                        <div>{{ $visit->employee->prenom ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Age</div>
                        <div>{{ $visit->employee->age ?? '—' }} ans </div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Sexe</div>
                        <div>{{ $visit->employee->sexe ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Matricule</div>
                        <div>{{ $visit->employee->matricule ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Téléphone</div>
                        <div>{{ $visit->employee->telephone ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Direction</div>
                        <div>{{ $visit->employee->direction ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Poste</div>
                        <div>{{ $visit->employee->emploi_occupe ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Ancienneté</div>
                        <div>{{ $visit->employee->anciennete ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Délégation Régionale ou Département</div>
                        <div>{{ $visit->employee->delegation_r ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Délégation Départementale ou Service</div>
                        <div>{{ $visit->employee->service ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Unité communale</div>
                        <div>{{ $visit->employee->unite_communale ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Date visite</div>
                        <div>{{ $visit->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <ul class="mb-3 nav nav-tabs" role="tablist">
                @if ($user->role === 'med-taf')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical-pane"
                            type="button" role="tab" aria-controls="medical-pane" aria-selected="true">
                            Médicale
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $user->role === 'med-taf' ? '' : 'active' }}" id="qhse-tab"
                        data-bs-toggle="tab" data-bs-target="#qhse-pane" type="button" role="tab"
                        aria-controls="qhse-pane" aria-selected="{{ $user->role === 'med-taf' ? 'false' : 'true' }}">
                        QHSE
                    </button>
                </li>
            </ul>

            @php
                $qhse = $visit->qhse;
            @endphp

            <div class="tab-content">
                @if ($user->role === 'med-taf')
                    <div class="tab-pane fade show active" id="medical-pane" role="tabpanel" aria-labelledby="medical-tab">
                        <div class="mb-4 bo-card">
                            <div class="mb-3 fw-semibold">Résumé médical</div>
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="bo-muted">Taille</div>
                                    <div>{{ $visit->taille ?? '—' }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="bo-muted">Poids</div>
                                    <div>{{ $visit->poids ?? '—' }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="bo-muted">IMC</div>
                                    <div>{{ $visit->imc ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="bo-muted">Tension</div>
                                    <div>{{ $visit->tension ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="bo-muted">Avis médical</div>
                                    <div>{{ $visit->avis ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="bo-muted">Stress</div>
                                    <div>{{ $visit->stress ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="bo-muted">Sommeil</div>
                                    <div>{{ $visit->sommeil ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="bo-muted">Charge de travail</div>
                                    <div>{{ $visit->charge_travail ?? '—' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="bo-muted">Soutien</div>
                                    <div>{{ $visit->soutien ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 bo-card">
                            <div class="mb-2 fw-semibold">Antécédents</div>
                            @if (is_array($visit->antecedents) && count($visit->antecedents))
                                <div class="flex-wrap gap-2 d-flex">
                                    @foreach ($visit->antecedents as $item)
                                        <span class="bo-pill">{{ $item }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="bo-muted">—</div>
                            @endif
                            @if ($visit->antecedents_precisions)
                                <div class="mt-2"><strong>Précisions :</strong> {{ $visit->antecedents_precisions }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-4 bo-card">
                            <div class="mb-2 fw-semibold">Observations</div>
                            <div>{{ $visit->observations ?? '—' }}</div>
                        </div>
                    </div>
                @endif

                <div class="tab-pane fade {{ $user->role === 'med-taf' ? '' : 'show active' }}" id="qhse-pane"
                    role="tabpanel" aria-labelledby="qhse-tab">
                    <div class="mb-4 bo-card">
                        <div class="mb-3 fw-semibold">QHSE / SST (employé)</div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="bo-muted">Contraintes manutention</div>
                                @if (is_array($qhse->contrainte_manutention) && count($qhse->contrainte_manutention))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->contrainte_manutention as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Contraintes postures</div>
                                @if (is_array($qhse->contrainte_postures) && count($qhse->contrainte_postures))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->contrainte_postures as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Nuisances physiques</div>
                                @if (is_array($qhse->nuisances_physiques) && count($qhse->nuisances_physiques))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->nuisances_physiques as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Nuisances chimiques</div>
                                @if (is_array($qhse->nuisances_chimiques) && count($qhse->nuisances_chimiques))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->nuisances_chimiques as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Risques mécaniques</div>
                                @if (is_array($qhse->risques_mecaniques) && count($qhse->risques_mecaniques))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->risques_mecaniques as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Organisation travail</div>
                                @if (is_array($qhse->organisation_travail) && count($qhse->organisation_travail))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->organisation_travail as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">EPI disponibilité</div>
                                @if (is_array($qhse->epi_disponibilite) && count($qhse->epi_disponibilite))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->epi_disponibilite as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">EPI utilisation</div>
                                <div>{{ $qhse->epi_utilisation ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">EPI difficultés</div>
                                @if (is_array($qhse->epi_difficultes) && count($qhse->epi_difficultes))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->epi_difficultes as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Formation SST</div>
                                @if (is_array($qhse->formation_sst) && count($qhse->formation_sst))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->formation_sst as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="bo-muted">Appréciation poste</div>
                                <div>{{ $qhse->appreciation_poste ?? '—' }}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="bo-muted">Synthèse facteurs</div>
                                @if (is_array($qhse->synthese_facteurs) && count($qhse->synthese_facteurs))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->synthese_facteurs as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="bo-muted">Synthèse actions</div>
                                @if (is_array($qhse->synthese_actions) && count($qhse->synthese_actions))
                                    <div class="flex-wrap gap-2 d-flex">
                                        @foreach ($qhse->synthese_actions as $item)
                                            <span class="bo-pill">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bo-muted">—</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
