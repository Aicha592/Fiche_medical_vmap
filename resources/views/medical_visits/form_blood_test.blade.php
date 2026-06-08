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

@if (session('saved_files'))
    <div class="mt-3">
        <h6>Fichiers envoyés :</h6>
        <div class="d-flex flex-wrap" style="gap:8px">
            @foreach (session('saved_files') as $path)
                @php $url = asset('storage/' . $path); $ext = pathinfo($path, PATHINFO_EXTENSION); @endphp
                <div class="border rounded p-1 bg-white" style="width:120px;text-align:center">
                    @if (in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                        <img src="{{ $url }}" style="max-width:100%;height:80px;object-fit:cover" />
                    @else
                        <div style="height:80px;display:flex;align-items:center;justify-content:center;font-weight:700">{{ strtoupper($ext) }}</div>
                    @endif
                    <div style="font-size:12px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ basename($path) }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif


<div class="medical-modal">
    <div class="text-white medical-header">
        <h5 class="modal-title">Bilan Sanguin – VISITE MÉDICALE ANNUELLE DU PERSONNEL (VMAP) 2026</h5>
    </div>

    <form method="POST" action="{{ route('medical-visits.store_blood_test') }}" class="needs-validation"
        novalidate enctype="multipart/form-data">
                @csrf

                <div class="modal-body medical-body">
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
                                <span class="section-index">I</span>
                                <h5>Bilan</h5>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">UREE (g/L)</label>
                                    <input type="number" step="0.01" name="uree" id="uree"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">CREAT (mg/L)</label>
                                    <input type="number" step="0.1" name="creat" id="creat"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">ASAT (UI/L)</label>
                                    <input type="number" step="0.1" name="asat" id="asat" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">ALAT (UI/L)</label>
                                    <input type="number" step="0.1" name="alat" id="alat" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">AGHBS</label>
                                    <div class="option-group">
                                        <label class="option-line"><input class="form-check-input" type="radio"
                                                name="aghbs" value="Positif"> Positif</label>
                                        <label class="option-line"><input class="form-check-input" type="radio"
                                                name="aghbs" value="Négatif"> Négatif</label>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">CHOL TOT (g/L)</label>
                                    <input type="number" step="0.1" name="chol" id="chol"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">TG (g/L)</label>
                                    <input type="number" step="0.1" name="tg" id="tg" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">GAJ (g/L)</label>
                                    <input type="number" step="0.1" name="gaj" id="gaj" class="form-control"
                                        required>
                                </div>
                            </div>
                        </section>

                        <section class="medical-section">
                            <div class="section-title">
                                <span class="section-index">II</span>
                                <h5>NFS</h5>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">HB (g/dl)</label>
                                    <input type="number" step="0.01" name="hb" id="hb"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">HCT (%)</label>
                                    <input type="number" step="0.1" name="hct" id="hct"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">GB (/mm3)</label>
                                    <input type="number" step="0.1" name="gb" id="gb" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label">PLT (/mm3)</label>
                                    <input type="number" step="0.1" name="plt" id="plt" class="form-control"
                                        required>
                                </div>
                            </div>
                        </section>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="text-white btn btn-primary-custom">
                                Enregistrer
                            </button>
                        </div>
                    </div>
            </form>
</div>

<script>
    (function() {
        const input = document.getElementById('filesInput');
        if (!input) return;

        const preview = document.getElementById('filesPreview');
        const dt = new DataTransfer();

        function renderPreview() {
            preview.innerHTML = '';
            const files = Array.from(dt.files);

            files.forEach((file, idx) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'border rounded p-1 bg-white';
                wrapper.style.width = '120px';
                wrapper.style.textAlign = 'center';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '100%';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';
                    wrapper.appendChild(img);
                } else {
                    const icon = document.createElement('div');
                    icon.innerText = 'PDF';
                    icon.style.height = '80px';
                    icon.style.display = 'flex';
                    icon.style.alignItems = 'center';
                    icon.style.justifyContent = 'center';
                    icon.style.fontWeight = '700';
                    wrapper.appendChild(icon);
                }

                const name = document.createElement('div');
                name.style.fontSize = '12px';
                name.style.overflow = 'hidden';
                name.style.textOverflow = 'ellipsis';
                name.style.whiteSpace = 'nowrap';
                name.style.width = '100%';
                name.textContent = file.name;
                wrapper.appendChild(name);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-outline-danger mt-1';
                removeBtn.style.width = '100%';
                removeBtn.textContent = 'Retirer';
                removeBtn.addEventListener('click', function() {
                    dt.items.remove(idx);
                    input.files = dt.files;
                    renderPreview();
                });
                wrapper.appendChild(removeBtn);

                preview.appendChild(wrapper);
            });

            if (files.length === 0) {
                input.value = '';
            }
        }

        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files || []);
            files.forEach(file => dt.items.add(file));
            input.files = dt.files;
            renderPreview();
        });

        // Keep file preview only; normal form submission will handle upload.
    })();
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
