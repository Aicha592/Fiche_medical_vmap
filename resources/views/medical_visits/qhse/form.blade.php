@extends('layouts.app')

@section('content')
<style>
    :root {
        --green-dark: #467049;
        --green-light: #aeca5f;
        --ink: #000000;
        --ink-soft: #626160;
        --paper: #ffffff;
        --shadow-soft: 0 8px 24px rgba(0, 0, 0, 0.12);
        --radius-lg: 18px;
        --radius-md: 12px;
        --radius-sm: 8px;
        --font-title: "LOEW HEAVY", "LOEW Heavy", "Times New Roman", serif;
        --font-strong: "ALLER BOLD", "Aller Bold", "Arial Black", sans-serif;
        --font-body: "ALLER REGULAR", "Aller Regular", "Arial", sans-serif;
        --font-light: "ALLER LIGHT", "Aller Light", "Arial", sans-serif;
        --font-accent: "HAND OF SEAN", "Hand of Sean", "Comic Sans MS", cursive;
    }

    .qhse-hero {
        background: linear-gradient(130deg, rgba(70, 112, 73, 0.12), rgba(174, 202, 95, 0.25));
        border-radius: var(--radius-lg);
        padding: 22px 26px;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(70, 112, 73, 0.2);
        margin-bottom: 20px;
    }

    .qhse-hero h3 {
        font-family: var(--font-title);
        letter-spacing: 0.7px;
        color: var(--green-dark);
        margin-bottom: 6px;
    }

    .qhse-hero p {
        font-family: var(--font-body);
        color: var(--ink-soft);
        margin: 0;
    }

    .medical-section {
        background: var(--paper);
        border-radius: var(--radius-md);
        padding: 22px;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(70, 112, 73, 0.12);
        margin-bottom: 20px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .section-index {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--green-light);
        color: var(--ink);
        font-family: var(--font-strong);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 0 0 2px rgba(70, 112, 73, 0.25);
    }

    .section-title h5,
    .section-title h6 {
        margin: 0;
        font-family: var(--font-title);
        text-transform: uppercase;
        font-size: 1rem;
        color: var(--green-dark);
    }

    .medical-section h6 {
        font-family: var(--font-strong);
        color: var(--green-dark);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .form-label {
        font-family: var(--font-strong);
        letter-spacing: 0.4px;
        color: var(--ink);
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .form-control,
    .form-select {
        border-radius: var(--radius-sm);
        border-color: rgba(70, 112, 73, 0.25);
        background-color: #fbfcf6;
        font-family: var(--font-body);
        padding: 10px 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--green-dark);
        box-shadow: 0 0 0 0.2rem rgba(70, 112, 73, 0.2);
    }

    input[type="checkbox"],
    input[type="radio"] {
        accent-color: var(--green-dark);
        width: 1.05rem;
        height: 1.05rem;
        margin-right: 6px;
    }

    .option-line {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        margin: 4px 10px 4px 0;
        border-radius: 999px;
        background: rgba(174, 202, 95, 0.2);
        border: 1px solid rgba(70, 112, 73, 0.2);
        font-family: var(--font-light);
        cursor: pointer;
    }

    .option-line input {
        margin: 0;
    }

    .option-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 10px;
    }

    .btn-primary-custom {
        background-color: var(--green-dark);
        border-color: var(--green-dark);
        color: #fff;
        font-family: var(--font-strong);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary-custom:hover {
        background-color: #355c39;
        border-color: #355c39;
        color: #fff;
    }

    .btn-secondary-custom {
        background-color: #e9eadf;
        border-color: rgba(70, 112, 73, 0.2);
        color: var(--ink);
    }

    .btn-secondary-custom:hover {
        background-color: #dfe1d1;
        border-color: rgba(70, 112, 73, 0.4);
        color: var(--ink);
    }

    .note-accent {
        font-family: var(--font-accent);
        color: var(--green-dark);
        font-size: 0.95rem;
    }
</style>

@php
    $agentName = trim(($user?->nom ?? '') . ' ' . ($user?->prenom ?? ''));
    if ($agentName === '') {
        $agentName = $user?->name ?? '—';
    }

    $checked = function ($value, $array) {
        return is_array($array) && in_array($value, $array, true);
    };
@endphp

<div class="qhse-hero">
    <h3>Questionnaire QHSE / SST</h3>
    <p>Agent : {{ $agentName }} — Matricule : {{ $user?->matricule ?? '—' }}</p>
    <p class="note-accent mb-0">Compléter uniquement les rubriques QHSE.</p>
</div>

<form method="POST" action="{{ route('medical-visits.qhse.update', $visit) }}">
    @csrf
    @method('PUT')

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">II</span>
            <h5>Contraintes physiques et ergonomiques</h5>
        </div>
        <p><b>1. Manutention et efforts physiques</b></p>
        <div class="option-group mb-3">
            <label class="option-line"><input type="checkbox" name="qhse_manutention[]" value="Port manuel de charges lourdes" {{ $checked('Port manuel de charges lourdes', $visit->contrainte_manutention) ? 'checked' : '' }}> Port manuel de charges lourdes</label>
            <label class="option-line"><input type="checkbox" name="qhse_manutention[]" value="Soulèvement fréquent" {{ $checked('Soulèvement fréquent', $visit->contrainte_manutention) ? 'checked' : '' }}> Soulèvement fréquent</label>
            <label class="option-line"><input type="checkbox" name="qhse_manutention[]" value="Poussée / traction" {{ $checked('Poussée / traction', $visit->contrainte_manutention) ? 'checked' : '' }}> Poussée / traction</label>
            <label class="option-line"><input type="checkbox" name="qhse_manutention[]" value="Efforts physiques intenses" {{ $checked('Efforts physiques intenses', $visit->contrainte_manutention) ? 'checked' : '' }}> Efforts physiques intenses</label>
        </div>

        <p><b>2. Postures et gestes</b></p>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_postures[]" value="Postures pénibles" {{ $checked('Postures pénibles', $visit->contrainte_postures) ? 'checked' : '' }}> Postures pénibles</label>
            <label class="option-line"><input type="checkbox" name="qhse_postures[]" value="Gestes répétitifs" {{ $checked('Gestes répétitifs', $visit->contrainte_postures) ? 'checked' : '' }}> Gestes répétitifs</label>
            <label class="option-line"><input type="checkbox" name="qhse_postures[]" value="Travail prolongé debout" {{ $checked('Travail prolongé debout', $visit->contrainte_postures) ? 'checked' : '' }}> Travail prolongé debout</label>
            <label class="option-line"><input type="checkbox" name="qhse_postures[]" value="Travail accroupi" {{ $checked('Travail accroupi', $visit->contrainte_postures) ? 'checked' : '' }}> Travail accroupi</label>
            <label class="option-line"><input type="checkbox" name="qhse_postures[]" value="Vibrations" {{ $checked('Vibrations', $visit->contrainte_postures) ? 'checked' : '' }}> Vibrations</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">III</span>
            <h5>Expositions aux nuisances professionnelles</h5>
        </div>
        <p><b>1. Nuisances physiques</b></p>
        <div class="option-group mb-3">
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_physiques[]" value="Bruit élevé" {{ $checked('Bruit élevé', $visit->nuisances_physiques) ? 'checked' : '' }}> Bruit élevé</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_physiques[]" value="Chaleur / soleil intense" {{ $checked('Chaleur / soleil intense', $visit->nuisances_physiques) ? 'checked' : '' }}> Chaleur / soleil intense</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_physiques[]" value="Pluie / humidité" {{ $checked('Pluie / humidité', $visit->nuisances_physiques) ? 'checked' : '' }}> Pluie / humidité</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_physiques[]" value="Froid / Vent" {{ $checked('Froid / Vent', $visit->nuisances_physiques) ? 'checked' : '' }}> Froid / Vent</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_physiques[]" value="Éclairage insuffisant" {{ $checked('Éclairage insuffisant', $visit->nuisances_physiques) ? 'checked' : '' }}> Éclairage insuffisant</label>
        </div>

        <p><b>2. Nuisances chimiques et biologiques</b></p>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_chimiques[]" value="Poussières" {{ $checked('Poussières', $visit->nuisances_chimiques) ? 'checked' : '' }}> Poussières</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_chimiques[]" value="Odeurs fortes" {{ $checked('Odeurs fortes', $visit->nuisances_chimiques) ? 'checked' : '' }}> Odeurs fortes</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_chimiques[]" value="Lixiviats" {{ $checked('Lixiviats', $visit->nuisances_chimiques) ? 'checked' : '' }}> Lixiviats</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_chimiques[]" value="Produits chimiques" {{ $checked('Produits chimiques', $visit->nuisances_chimiques) ? 'checked' : '' }}> Produits chimiques</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_chimiques[]" value="Déchets médicaux" {{ $checked('Déchets médicaux', $visit->nuisances_chimiques) ? 'checked' : '' }}> Déchets médicaux</label>
            <label class="option-line"><input type="checkbox" name="qhse_nuisances_chimiques[]" value="Agents biologiques" {{ $checked('Agents biologiques', $visit->nuisances_chimiques) ? 'checked' : '' }}> Agents biologiques</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">IV</span>
            <h5>Risques mécaniques et accidentels</h5>
        </div>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_risques[]" value="Circulation routière" {{ $checked('Circulation routière', $visit->risques_mecaniques) ? 'checked' : '' }}> Circulation routière</label>
            <label class="option-line"><input type="checkbox" name="qhse_risques[]" value="Risque de chute" {{ $checked('Risque de chute', $visit->risques_mecaniques) ? 'checked' : '' }}> Risque de chute</label>
            <label class="option-line"><input type="checkbox" name="qhse_risques[]" value="Coupures / piqûres" {{ $checked('Coupures / piqûres', $visit->risques_mecaniques) ? 'checked' : '' }}> Coupures / piqûres</label>
            <label class="option-line"><input type="checkbox" name="qhse_risques[]" value="Coincement / écrasement" {{ $checked('Coincement / écrasement', $visit->risques_mecaniques) ? 'checked' : '' }}> Coincement / écrasement</label>
            <label class="option-line"><input type="checkbox" name="qhse_risques[]" value="Incendie / explosion" {{ $checked('Incendie / explosion', $visit->risques_mecaniques) ? 'checked' : '' }}> Incendie / explosion</label>
            <label class="option-line"><input type="checkbox" name="qhse_risques[]" value="Utilisation d’engins ou machines" {{ $checked('Utilisation d’engins ou machines', $visit->risques_mecaniques) ? 'checked' : '' }}> Utilisation d’engins ou machines</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">V</span>
            <h5>Organisation du travail</h5>
        </div>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_organisation[]" value="Travail de nuit" {{ $checked('Travail de nuit', $visit->organisation_travail) ? 'checked' : '' }}> Travail de nuit</label>
            <label class="option-line"><input type="checkbox" name="qhse_organisation[]" value="Horaires décalés" {{ $checked('Horaires décalés', $visit->organisation_travail) ? 'checked' : '' }}> Horaires décalés</label>
            <label class="option-line"><input type="checkbox" name="qhse_organisation[]" value="Travail en rotation" {{ $checked('Travail en rotation', $visit->organisation_travail) ? 'checked' : '' }}> Travail en rotation</label>
            <label class="option-line"><input type="checkbox" name="qhse_organisation[]" value="Travail isolé" {{ $checked('Travail isolé', $visit->organisation_travail) ? 'checked' : '' }}> Travail isolé</label>
            <label class="option-line"><input type="checkbox" name="qhse_organisation[]" value="Pression temporelle élevée" {{ $checked('Pression temporelle élevée', $visit->organisation_travail) ? 'checked' : '' }}> Pression temporelle élevée</label>
            <label class="option-line"><input type="checkbox" name="qhse_organisation[]" value="Manque de pauses" {{ $checked('Manque de pauses', $visit->organisation_travail) ? 'checked' : '' }}> Manque de pauses</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">VI</span>
            <h5>EPI</h5>
        </div>
        <p><b>1. Mise à disposition </b></p>
        <div class="option-group mb-3">
            <label class="option-line"><input type="checkbox" name="qhse_epi_dispo[]" value="Casque" {{ $checked('Casque', $visit->epi_disponibilite) ? 'checked' : '' }}> Casque</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_dispo[]" value="Gants" {{ $checked('Gants', $visit->epi_disponibilite) ? 'checked' : '' }}> Gants</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_dispo[]" value="Bottes" {{ $checked('Bottes', $visit->epi_disponibilite) ? 'checked' : '' }}> Bottes</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_dispo[]" value="Masque" {{ $checked('Masque', $visit->epi_disponibilite) ? 'checked' : '' }}> Masque</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_dispo[]" value="Gilet haute visibilité" {{ $checked('Gilet haute visibilité', $visit->epi_disponibilite) ? 'checked' : '' }}> Gilet haute visibilité</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_dispo[]" value="Autres" {{ $checked('Autres', $visit->epi_disponibilite) ? 'checked' : '' }}> Autres</label>
        </div>

        <p><b>2. Utilisation </b></p>
        <div class="option-group mb-3">
            <label class="option-line"><input type="radio" name="qhse_epi_utilisation" value="Toujours" {{ $visit->epi_utilisation === 'Toujours' ? 'checked' : '' }}> Toujours</label>
            <label class="option-line"><input type="radio" name="qhse_epi_utilisation" value="Souvent" {{ $visit->epi_utilisation === 'Souvent' ? 'checked' : '' }}> Souvent</label>
            <label class="option-line"><input type="radio" name="qhse_epi_utilisation" value="Rarement" {{ $visit->epi_utilisation === 'Rarement' ? 'checked' : '' }}> Rarement</label>
            <label class="option-line"><input type="radio" name="qhse_epi_utilisation" value="Jamais" {{ $visit->epi_utilisation === 'Jamais' ? 'checked' : '' }}> Jamais</label>
        </div>

        <p><b>3. Difficultés rencontrées </b></p>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_epi_difficulte[]" value="Inconfort" {{ $checked('Inconfort', $visit->epi_difficultes) ? 'checked' : '' }}> Inconfort</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_difficulte[]" value="Inadaptation" {{ $checked('Inadaptation', $visit->epi_difficultes) ? 'checked' : '' }}> Inadaptation</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_difficulte[]" value="Usure rapide" {{ $checked('Usure rapide', $visit->epi_difficultes) ? 'checked' : '' }}> Usure rapide</label>
            <label class="option-line"><input type="checkbox" name="qhse_epi_difficulte[]" value="Indisponibilité" {{ $checked('Indisponibilité', $visit->epi_difficultes) ? 'checked' : '' }}> Indisponibilité</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">VII</span>
            <h5>Formation et information SST</h5>
        </div>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_formation[]" value="Formation SST reçue" {{ $checked('Formation SST reçue', $visit->formation_sst) ? 'checked' : '' }}> Formation SST reçue</label>
            <label class="option-line"><input type="checkbox" name="qhse_formation[]" value="Sensibilisation aux risques" {{ $checked('Sensibilisation aux risques', $visit->formation_sst) ? 'checked' : '' }}> Sensibilisation aux risques</label>
            <label class="option-line"><input type="checkbox" name="qhse_formation[]" value="Formation EPI" {{ $checked('Formation EPI', $visit->formation_sst) ? 'checked' : '' }}> Formation EPI</label>
            <label class="option-line"><input type="checkbox" name="qhse_formation[]" value="Formation conduite" {{ $checked('Formation conduite', $visit->formation_sst) ? 'checked' : '' }}> Formation conduite</label>
            <label class="option-line"><input type="checkbox" name="qhse_formation[]" value="Aucune formation récente" {{ $checked('Aucune formation récente', $visit->formation_sst) ? 'checked' : '' }}> Aucune formation récente</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">VIII</span>
            <h5>Appréciation globale</h5>
        </div>
        <div class="option-group">
            <label class="option-line"><input type="radio" name="qhse_appreciation" value="Faible risque" {{ $visit->appreciation_poste === 'Faible risque' ? 'checked' : '' }}> Faible risque</label>
            <label class="option-line"><input type="radio" name="qhse_appreciation" value="Risque modéré" {{ $visit->appreciation_poste === 'Risque modéré' ? 'checked' : '' }}> Risque modéré</label>
            <label class="option-line"><input type="radio" name="qhse_appreciation" value="Risque élevé" {{ $visit->appreciation_poste === 'Risque élevé' ? 'checked' : '' }}> Risque élevé</label>
            <label class="option-line"><input type="radio" name="qhse_appreciation" value="Risque très élevé" {{ $visit->appreciation_poste === 'Risque très élevé' ? 'checked' : '' }}> Risque très élevé</label>
        </div>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">IX</span>
            <h5>Observations / Suggestions</h5>
        </div>
        <textarea name="qhse_observations" class="form-control" rows="4">{{ $visit->observations_qhse }}</textarea>
    </section>

    <section class="medical-section">
        <div class="section-title">
            <span class="section-index">X</span>
            <h5>Synthèse QHSE</h5>
        </div>
        <p><b>Poste à risque identifié </b> </p>
        <div class="option-group mb-3">
            <label class="option-line"><input type="radio" name="qhse_synthese_risque" value="Oui" {{ $visit->synthese_risque === 'Oui' ? 'checked' : '' }}> Oui</label>
            <label class="option-line"><input type="radio" name="qhse_synthese_risque" value="Non" {{ $visit->synthese_risque === 'Non' ? 'checked' : '' }}> Non</label>
        </div>

        <p><b>Facteurs de risques dominants </b></p>
        <div class="option-group mb-3">
            <label class="option-line"><input type="checkbox" name="qhse_synthese_facteurs[]" value="Ergonomie" {{ $checked('Ergonomie', $visit->synthese_facteurs) ? 'checked' : '' }}> Ergonomie</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_facteurs[]" value="Physique" {{ $checked('Physique', $visit->synthese_facteurs) ? 'checked' : '' }}> Physique</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_facteurs[]" value="Chimique" {{ $checked('Chimique', $visit->synthese_facteurs) ? 'checked' : '' }}> Chimique</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_facteurs[]" value="Biologique" {{ $checked('Biologique', $visit->synthese_facteurs) ? 'checked' : '' }}> Biologique</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_facteurs[]" value="Organisationnel" {{ $checked('Organisationnel', $visit->synthese_facteurs) ? 'checked' : '' }}> Organisationnel</label>
        </div>

        <p><b>Actions recommandées </b></p>
        <div class="option-group">
            <label class="option-line"><input type="checkbox" name="qhse_synthese_actions[]" value="Étude ergonomique" {{ $checked('Étude ergonomique', $visit->synthese_actions) ? 'checked' : '' }}> Étude ergonomique</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_actions[]" value="Aménagement de poste" {{ $checked('Aménagement de poste', $visit->synthese_actions) ? 'checked' : '' }}> Aménagement de poste</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_actions[]" value="Renforcement EPI" {{ $checked('Renforcement EPI', $visit->synthese_actions) ? 'checked' : '' }}> Renforcement EPI</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_actions[]" value="Formation ciblée" {{ $checked('Formation ciblée', $visit->synthese_actions) ? 'checked' : '' }}> Formation ciblée</label>
            <label class="option-line"><input type="checkbox" name="qhse_synthese_actions[]" value="Suivi SST" {{ $checked('Suivi SST', $visit->synthese_actions) ? 'checked' : '' }}> Suivi SST</label>
        </div>
    </section>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('medical-visits.qhse.index') }}" class="btn btn-secondary-custom">⬅ Retour</a>
        <button type="submit" class="btn btn-primary-custom">Enregistrer QHSE</button>
    </div>
</form>
@endsection
