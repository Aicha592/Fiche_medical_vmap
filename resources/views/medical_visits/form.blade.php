<style>
    :root {
        --green-dark: #467049;
        --green-light: #aeca5f;
        --ink: #000000;
        --ink-soft: #626160;
        --paper: #ffffff;
        --shadow: 0 16px 40px rgba(70, 112, 73, 0.18);
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

    .medical-modal {
        background: radial-gradient(1200px 500px at 10% -20%, rgba(174, 202, 95, 0.35), transparent),
            radial-gradient(900px 420px at 110% 0%, rgba(70, 112, 73, 0.28), transparent),
            #f7f8f2;
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .medical-header {
        background: linear-gradient(135deg, var(--green-dark), #36583a);
        border-bottom: 4px solid rgba(174, 202, 95, 0.8);
        padding: 20px 28px;
    }

    .medical-header .modal-title {
        font-family: var(--font-title);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        font-size: 1.05rem;
    }

    .medical-header .btn-close,
    .bg-recap-custom .btn-close {
        filter: invert(1);
        opacity: 0.9;
    }

    .medical-body {
        padding: 28px;
        color: var(--ink);
        font-family: var(--font-body);
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

    .btn-recap-custom {
        background-color: var(--green-light);
        border-color: var(--green-light);
        color: var(--ink);
        font-family: var(--font-strong);
    }

    .btn-recap-custom:hover {
        background-color: #9fbb55;
        color: var(--ink);
    }

    .bg-recap-custom {
        background-color: var(--green-dark);
    }

    .recap-title {
        font-family: var(--font-title);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .note-accent {
        font-family: var(--font-accent);
        color: var(--green-dark);
        font-size: 0.95rem;
    }

    .medical-hero {
        background: linear-gradient(130deg, rgba(70, 112, 73, 0.12), rgba(174, 202, 95, 0.25));
        border-radius: var(--radius-lg);
        padding: 22px 26px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(70, 112, 73, 0.2);
    }

    .medical-hero h3 {
        font-family: var(--font-title);
        letter-spacing: 0.7px;
        color: var(--green-dark);
        margin-bottom: 6px;
    }

    .medical-hero p {
        font-family: var(--font-light);
        color: var(--ink-soft);
        margin: 0;
    }

    .medical-search {
        background: var(--paper);
        border-radius: var(--radius-md);
        padding: 20px 24px;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(70, 112, 73, 0.12);
    }

    .medical-search .list-group-item {
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
        border: 1px solid rgba(70, 112, 73, 0.12);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .medical-search .list-group-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(70, 112, 73, 0.12);
    }

    #recapBody {
        font-family: var(--font-body);
    }

    #recapBody h6 {
        font-family: var(--font-strong);
        color: var(--green-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #recapBody .recap-section {
        background: var(--paper);
        border-radius: var(--radius-md);
        padding: 16px 18px;
        border: 1px solid rgba(70, 112, 73, 0.12);
        box-shadow: var(--shadow-soft);
        margin-bottom: 16px;
    }

    #recapBody .recap-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    #recapBody .recap-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--green-light);
        color: var(--ink);
        font-family: var(--font-strong);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #recapBody .recap-icon {
        width: 20px;
        height: 20px;
        color: var(--green-dark);
    }

    #recapBody .list-group-item {
        border-radius: var(--radius-sm);
        border: 1px solid rgba(70, 112, 73, 0.12);
        margin-bottom: 8px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        font-family: var(--font-body);
    }

    #recapBody .list-group {
        border: 0;
        padding-left: 0;
    }

    .recap-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-family: var(--font-strong);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: 1px solid transparent;
    }

    .recap-badge-low {
        background: rgba(174, 202, 95, 0.3);
        color: var(--green-dark);
        border-color: rgba(70, 112, 73, 0.25);
    }

    .recap-badge-mid {
        background: rgba(70, 112, 73, 0.12);
        color: #2b3f2f;
        border-color: rgba(70, 112, 73, 0.3);
    }

    .recap-badge-high {
        background: rgba(0, 0, 0, 0.08);
        color: var(--ink);
        border-color: rgba(0, 0, 0, 0.2);
    }

    .recap-badge-very-high {
        background: rgba(0, 0, 0, 0.15);
        color: var(--ink);
        border-color: rgba(0, 0, 0, 0.35);
    }

    .recap-badge-yes {
        background: rgba(70, 112, 73, 0.2);
        color: var(--green-dark);
        border-color: rgba(70, 112, 73, 0.45);
    }

    .recap-badge-no {
        background: rgba(0, 0, 0, 0.06);
        color: var(--ink-soft);
        border-color: rgba(0, 0, 0, 0.2);
    }

    @media print {
        body {
            background: #fff !important;
        }

        .modal-backdrop,
        #visitModal {
            display: none !important;
        }

        #recapModal {
            display: block !important;
            position: static !important;
        }

        #recapModal .modal-dialog {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
        }

        #recapModal .modal-content {
            box-shadow: none !important;
            border: 0 !important;
        }

        #recapModal .modal-header,
        #recapModal .modal-footer {
            border: 0 !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

@if (session('success'))
    <div class="mt-3 alert alert-success" id="successAlert">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
@endif


<!-- Modal Formulaire Visite Médicale -->
<div class="modal fade" id="visitModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content medical-modal">

            <div class="text-white modal-header medical-header">
                <h5 class="modal-title">FICHE MÉDICALE – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP) 2026</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="visitForm" method="POST" action="{{ route('medical-visits.store') }}" class="needs-validation"
                novalidate>
                @csrf
                <input type="hidden" name="download_pdf" id="download_pdf" value="0">

                <div class="modal-body medical-body">
                    <div id="stepMedical">
                        <!-- I. IDENTIFICATION -->
                        <div class="qhse-hero">
                            <h3>Identification de l’agent</h3>
                            <p>Agent : <span id="agent_nom_display">—</span> — Matricule :
                                <span id="agent_matricule_display">—</span>
                            </p>
                            <p class="mb-0 note-accent">
                                Sexe : <span id="agent_sexe_display">—</span> • Âge :
                                <span id="agent_age_display">—</span> • Poste :
                                <span id="agent_poste_display">—</span>
                            </p>
                        </div>

                        <div class="visually-hidden">
                            <input type="text" id="agent_nom" readonly>
                            <input type="hidden" name="employee_id" id="agent_employee_id">
                            <input type="text" id="agent_matricule" readonly>
                            <input type="text" id="agent_sexe" readonly>
                            <input type="number" id="agent_age" name="age" readonly>
                            <input type="text" id="agent_direction" name="direction" readonly>
                            <input type="text" id="agent_delegation" readonly>
                            <input type="text" id="agent_service" readonly>
                            <input type="text" id="agent_unite_communale" readonly>
                            <input type="text" id="agent_poste" name="poste" readonly>
                            <input type="text" id="agent_anciennete" name="anciennete" readonly>
                            <input type="text" id="agent_date_naissance" readonly>
                            <input type="text" id="agent_date_embauche" readonly>
                            <input type="text" id="agent_date_passage" readonly>
                            <input type="text" id="agent_telephone" readonly>
                        </div>

                        <!-- II. ANTÉCÉDENTS -->
                        <section class="medical-section">
                            <div class="section-title">
                                <span class="section-index">II</span>
                                <h5>ANTÉCÉDENTS MÉDICAUX</h5>
                            </div>

                            <div class="mb-3 option-group">
                                <label class="option-line"><input type="checkbox" name="antecedents[]" value="Diabète">
                                    Diabète</label>
                                <label class="option-line"><input type="checkbox" name="antecedents[]"
                                        value="Asthme/ANS"> Asthme/ANS</label>
                                <label class="option-line"><input type="checkbox" name="antecedents[]"
                                        value="Cardiopathie"> Cardiopathie</label>
                                <label class="option-line"><input type="checkbox" name="antecedents[]"
                                        value="Traumato Orthopédie"> Traumato Orthopédie</label>
                                <label class="option-line"><input type="checkbox" name="antecedents[]" value="Autres">
                                    Autres</label>
                            </div>

                            <div class="mb-3 option-group">
                                <label class="option-line"><input type="checkbox" name="antecedents[]"
                                        value="Accident du travail"> Accident du travail</label>
                                <label class="option-line"><input type="checkbox" name="antecedents[]"
                                        value="Maladie professionnelle"> Maladie professionnelle</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Préciser</label>
                                <input type="text" name="antecedents_precisions" class="form-control">
                            </div>
                        </section>

                        <!-- III. EXAMEN CLINIQUE -->
                        <section class="medical-section">
                            <div class="section-title">
                                <span class="section-index">III</span>
                                <h5>EXAMEN CLINIQUE</h5>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Taille (cm)</label>
                                    <input type="number" step="0.01" name="taille" id="taille"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Poids (kg)</label>
                                    <input type="number" step="0.1" name="poids" id="poids"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label">IMC</label>
                                    <input type="text" name="imc" id="imc" class="form-control"
                                        readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tension artérielle</label>
                                <input type="text" name="tension" class="form-control" placeholder="Ex: 120 / 80"
                                    required>
                            </div>
                        </section>

                        <!-- IV. DEPITAGE RPS -->
                        <section class="medical-section">
                            <div class="section-title">
                                <span class="section-index">IV</span>
                                <h5>DÉPISTAGE RPS</h5>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Stress lié au travail</label><br>
                                <div class="option-group">
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="stress" value="Non" required> Non</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="stress" value="Parfois"> Parfois</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="stress" value="Oui"> Oui</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Troubles du sommeil</label><br>
                                <div class="option-group">
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="sommeil" value="Non" required> Non</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="sommeil" value="Parfois"> Parfois</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="sommeil" value="Oui"> Oui</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Charge de travail supportable</label><br>
                                <div class="option-group">
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="charge_travail" value="Oui" required> Oui</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="charge_travail" value="Variable"> Variable</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="charge_travail" value="Non"> Non</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Soutien hiérarchique</label><br>
                                <div class="option-group">
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="soutien" value="Oui" required> Oui</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="soutien" value="Peu"> Peu</label>
                                    <label class="option-line"><input class="form-check-input" type="radio"
                                            name="soutien" value="Pas du tout"> Pas du tout</label>
                                </div>
                            </div>
                        </section>

                        <!-- V. AVIS MEDICAL -->
                        <section class="medical-section">
                            <div class="section-title">
                                <span class="section-index">V</span>
                                <h5>AVIS MÉDICAL</h5>
                            </div>

                            <div class="mb-3">
                                <div class="option-group">
                                    <label class="option-line"><input type="radio" name="avis"
                                            value="Apte sans restriction" required> Apte sans restriction</label>
                                    <label class="option-line"><input type="radio" name="avis"
                                            value="Apte avec aménagement"> Apte avec aménagement</label>
                                    <label class="option-line"><input type="radio" name="avis"
                                            value="Inapte temporaire"> Inapte temporaire</label>
                                    <label class="option-line"><input type="radio" name="avis"
                                            value="Inapte définitif"> Inapte définitif</label>
                                </div>
                            </div>
                        </section>

                        <!-- VI. OBSERVATIONS -->
                        <section class="medical-section">
                            <div class="section-title">
                                <span class="section-index">VI</span>
                                <h5>OBSERVATIONS / RECOMMANDATIONS</h5>
                            </div>
                            <textarea name="observations" class="form-control" rows="4" required></textarea>
                            <p class="mt-2 mb-0 note-accent">Note clinique, recommandations, suivi proposé…</p>
                        </section>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" id="btnShowRecap" class="text-white btn btn-primary-custom">
                                Enregistrer
                            </button>
                        </div>
                    </div>





            </form>

        </div>

    </div>

</div>


<!-- MODAL RECAP -->
<div class="modal fade" id="recapModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="text-white modal-header bg-recap-custom">
                <h5 class="modal-title recap-title">Récapitulatif - Vérifiez avant d'enregistrer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="recapBody">
                <!-- Le contenu sera rempli par JS -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-custom no-print"
                    data-bs-dismiss="modal">Retour</button>
                <button type="button" id="printRecap" class="btn btn-secondary-custom no-print">
                    Imprimer
                </button>
                <button type="button" id="downloadPdf" class="btn btn-secondary-custom no-print">
                    Télécharger PDF
                </button>
                <button type="button" id="confirmSave" class="btn btn-recap-custom no-print">
                    Confirmer et Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>



<script>
    // ===== Recherche agent + ouverture du formulaire =====
    document.getElementById('searchAgent').addEventListener('input', function() {
        const query = this.value;

        if (query.length < 2) {
            document.getElementById('agentsResults').innerHTML = '';
            return;
        }

        fetch(`/employees/search?q=${query}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.forEach(agent => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action"
                           data-employee-id="${agent.employee_id}"
                           data-nom="${agent.name}"
                           data-matricule="${agent.matricule}"
                           data-sexe="${agent.sexe}"
                           data-age="${agent.age}"
                           data-date-naissance="${agent.date_naissance ?? ''}"
                           data-date-embauche="${agent.date_embauche ?? ''}"
                           data-direction="${agent.direction}"
                           data-delegation="${agent.delegation_r ?? ''}"
                           data-service="${agent.service ?? ''}"
                           data-unite-communale="${agent.unite_communale ?? ''}"
                           data-poste="${agent.poste}"
                           data-anciennete="${agent.anciennete}"
                           data-telephone="${agent.telephone ?? ''}"
                           data-date-passage="${agent.date_passage ?? ''}">
                            ${agent.name} - ${agent.matricule}
                        </a>
                    `;
                });
                document.getElementById('agentsResults').innerHTML = html;

                // click sur agent
                document.querySelectorAll('#agentsResults a').forEach(a => {
                    a.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.getElementById('agent_employee_id').value = this.dataset
                            .employeeId;
                        document.getElementById('agent_nom').value = this.dataset.nom;
                        document.getElementById('agent_matricule').value = this.dataset
                            .matricule;
                        document.getElementById('agent_sexe').value = this.dataset.sexe;
                        document.getElementById('agent_age').value = this.dataset.age;
                        document.getElementById('agent_date_naissance').value = this.dataset
                            .dateNaissance || '';
                        document.getElementById('agent_date_embauche').value = this.dataset
                            .dateEmbauche || '';
                        document.getElementById('agent_direction').value = this.dataset
                            .direction;
                        document.getElementById('agent_delegation').value = this.dataset
                            .delegation || '';
                        document.getElementById('agent_service').value = this.dataset
                            .service || '';
                        document.getElementById('agent_unite_communale').value = this
                            .dataset.uniteCommunale || '';
                        document.getElementById('agent_poste').value = this.dataset.poste;
                        document.getElementById('agent_anciennete').value = this.dataset
                            .anciennete;
                        document.getElementById('agent_date_passage').value = this.dataset
                            .datePassage || '';
                        document.getElementById('agent_telephone').value = this.dataset
                            .telephone || '';

                        const setText = (id, value) => {
                            const el = document.getElementById(id);
                            if (el) {
                                el.textContent = value && value !== '' ? value : '—';
                            }
                        };

                        setText('agent_nom_display', this.dataset.nom || '—');
                        setText('agent_matricule_display', this.dataset.matricule || '—');
                        setText('agent_sexe_display', this.dataset.sexe || '—');
                        setText('agent_age_display', this.dataset.age || '—');
                        setText('agent_poste_display', this.dataset.poste || '—');

                        // ouvrir modal
                        new bootstrap.Modal(document.getElementById('visitModal')).show();
                    });
                });
            });
    });
</script>


<!-- // ===== IMC ===== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function calculerIMC() {
            const tailleInput = document.getElementById('taille');
            const poidsInput = document.getElementById('poids');
            const imcInput = document.getElementById('imc');

            if (!tailleInput || !poidsInput || !imcInput) return;

            const tailleCm = parseFloat(tailleInput.value);
            const poidsKg = parseFloat(poidsInput.value);

            if (!tailleCm || !poidsKg) {
                imcInput.value = '';
                return;
            }

            const tailleM = tailleCm / 100;
            const imc = poidsKg / (tailleM * tailleM);

            imcInput.value = imc.toFixed(2);
        }

        document.addEventListener('input', function(e) {
            if (e.target.id === 'taille' || e.target.id === 'poids') {
                calculerIMC();
            }
        });

    });
</script>



<!-- // ===== RECAP AVANT ENREGISTREMENT ===== -->
<script>
    document.getElementById('btnShowRecap').addEventListener('click', function() {

        const form = document.getElementById('visitForm');
        let recapHtml = '';

        const sections = [{
                title: 'IDENTIFICATION',
                fields: ['agent_nom', 'agent_matricule', 'agent_sexe', 'agent_age', 'agent_date_naissance',
                    'agent_date_embauche', 'agent_direction', 'agent_delegation', 'agent_service',
                    'agent_unite_communale', 'agent_poste', 'agent_anciennete', 'agent_date_passage',
                    'agent_telephone'
                ]
            },
            {
                title: 'ANTÉCÉDENTS',
                nameEndsWith: 'antecedents'
            },
            {
                title: 'EXAMEN CLINIQUE',
                fields: ['taille', 'poids', 'imc', 'tension']
            },
            {
                title: 'DÉPISTAGE RPS',
                radios: ['stress', 'sommeil', 'charge_travail', 'soutien']
            },
            {
                title: 'AVIS MÉDICAL',
                radios: ['avis']
            },
            {
                title: 'OBSERVATIONS',
                textareas: ['observations']
            },
        ];

        const recapBadges = {
            'IDENTIFICATION': 'I',
            'ANTÉCÉDENTS': 'II',
            'EXAMEN CLINIQUE': 'III',
            'DÉPISTAGE RPS': 'IV',
            'AVIS MÉDICAL': 'V',
            'OBSERVATIONS': 'VI'
        };

        const recapIcons = {
            'IDENTIFICATION': '<svg class="recap-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"/></svg>',
            'ANTÉCÉDENTS': '<svg class="recap-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v16l4-4h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-6 8h-2V9H9V7h2V5h2v2h2v2h-2v2z"/></svg>',
            'EXAMEN CLINIQUE': '<svg class="recap-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-3H7v-2h3V9h2v3h3v2h-3v3z"/></svg>',
            'DÉPISTAGE RPS': '<svg class="recap-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v2h-2v-2zm2.07-7.75-.9.92C11.45 10.9 11 11.5 11 13h2v-.5c0-.55.45-1 1-1 1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2H9c0-2.21 1.79-4 4-4s4 1.79 4 4c0 1.54-.87 2.88-2.13 3.59z"/></svg>',
            'AVIS MÉDICAL': '<svg class="recap-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v14l4-4h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 10l-3-3 1.41-1.41L11 10.17l4.59-4.59L17 7l-6 6z"/></svg>',
            'OBSERVATIONS': '<svg class="recap-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41L18.37 3.29a.9959.9959 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>'
        };

        const radioLabels = {
            stress: 'Stress lié au travail',
            sommeil: 'Troubles du sommeil',
            charge_travail: 'Charge de travail supportable',
            soutien: 'Soutien hiérarchique',
            avis: 'Avis médical'
        };

        const fieldLabels = {
            agent_nom: 'Nom et Prénom',
            agent_matricule: 'Matricule',
            agent_sexe: 'Sexe',
            agent_age: 'Âge',
            agent_date_naissance: 'Date de naissance',
            agent_date_embauche: "Date d'embauche",
            agent_direction: 'Direction / Département / Service',
            agent_delegation: 'Délégation / Région',
            agent_service: 'Service',
            agent_unite_communale: 'Unité communale',
            agent_poste: 'Intitulé du poste occupé',
            agent_anciennete: 'Ancienneté au poste',
            agent_date_passage: 'Date de passage',
            agent_telephone: 'Téléphone',
            taille: 'Taille (cm)',
            poids: 'Poids (kg)',
            imc: 'IMC',
            tension: 'Tension artérielle',
            observations: 'Observations / Recommandations'
        };

        sections.forEach(section => {
            const badge = recapBadges[section.title] || '•';
            const icon = recapIcons[section.title] || '';
            recapHtml += `
            <div class="recap-section">
                <div class="recap-title-row">
                    <span class="recap-badge">${badge}</span>
                    ${icon}
                    <h6 class="mb-0 fw-bold">${section.title}</h6>
                </div>
                <ul class="mb-0 list-group">
        `;

            // Champs simples par ID
            section.fields?.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.value) {
                    const label = fieldLabels[id] || el.previousElementSibling?.innerText || id;
                    recapHtml +=
                        `<li class="list-group-item"><strong>${label} :</strong> ${el.value}</li>`;
                }
            });

            // Radios
            section.radios?.forEach(name => {
                const checked = form.querySelector(`input[name="${name}"]:checked`);
                if (checked) {
                    const label = radioLabels[name] || name;
                    recapHtml +=
                        `<li class="list-group-item"><strong>${label} :</strong> ${checked.value}</li>`;
                }
            });

            // Textarea
            section.textareas?.forEach(name => {
                const el = form.querySelector(`textarea[name="${name}"]`);
                if (el && el.value) {
                    const label = fieldLabels[name] || name;
                    recapHtml +=
                        `<li class="list-group-item"><strong>${label} :</strong> ${el.value}</li>`;
                }
            });

            // Checkboxes par suffixe
            if (section.nameEndsWith) {
                const checked = [...form.querySelectorAll(
                        `input[name="${section.nameEndsWith}[]"]:checked`)]
                    .map(c => c.value);
                if (checked.length) {
                    recapHtml += `<li class="list-group-item">${checked.join(', ')}</li>`;
                }
            }

            recapHtml += `</ul></div>`;
        });

        document.getElementById('recapBody').innerHTML = recapHtml;

        new bootstrap.Modal(document.getElementById('recapModal')).show();
    });

    document.getElementById('printRecap').addEventListener('click', function() {
        window.print();
    });

    document.getElementById('downloadPdf').addEventListener('click', function() {
        const form = document.getElementById('visitForm');
        const downloadInput = document.getElementById('download_pdf');

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        downloadInput.value = '1';
        const previousTarget = form.target;
        form.target = '_blank';
        form.submit();

        form.target = previousTarget || '';
        downloadInput.value = '0';
    });

    // document.getElementById('confirmSave').addEventListener('click', function () {
    //     document.getElementById('visitForm').submit();
    // });
</script>
<script>
    document.getElementById('confirmSave').addEventListener('click', function() {

        const form = document.getElementById('visitForm');

        // Si le formulaire n'est pas valide -> on arrête
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Fermer la modal récap
        bootstrap.Modal.getInstance(document.getElementById('recapModal')).hide();


        // Enregistrer
        form.submit();
    });
</script>

<script>
    (() => {
        'use strict';

        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

    });
</script>
