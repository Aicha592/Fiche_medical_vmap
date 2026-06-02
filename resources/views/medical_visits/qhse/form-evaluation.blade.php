<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUESTIONNAIRE QHSE / SST VMAP 2026 - SONAGED</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full font-sans antialiased text-gray-900">

    <div class="max-w-4xl px-4 mx-auto my-12 sm:px-6 lg:px-8" x-data="{
        step: 1,
        searchQuery: '',
        searchResults: [],
        employee: null,
    
        fetchEmployees() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }
            fetch(`/medical-qhse/search-employee?query=${encodeURIComponent(this.searchQuery)}`)
                .then(res => res.json())
                .then(data => { this.searchResults = data; });
        },
        selectEmployee(emp) {
            this.employee = emp;
            this.searchResults = [];
            this.searchQuery = `${emp.prenom} ${emp.nom} (${emp.matricule})`;
        },
        resetEmployee() {
            this.employee = null;
            this.searchQuery = '';
            this.searchResults = [];
            this.step = 1;
        }
    }">

        <div
            class="relative p-6 overflow-hidden text-center bg-white border-b border-gray-200 shadow-sm rounded-t-xl sm:p-8">
            <div class="absolute top-0 left-0 right-0 h-2 bg-emerald-600"></div>
            <span
                class="px-3 py-1 text-xs font-bold tracking-widest uppercase rounded-full text-emerald-600 bg-emerald-50">SONAGED
                - VMAP 2026</span>
            <h1 class="mt-3 text-2xl font-extrabold text-gray-900 sm:text-3xl">FICHE MÉDICALE & QUESTIONNAIRE QHSE</h1>
        </div>

        @if (session('success'))
            <div
                class="flex items-center p-4 mt-4 space-x-2 border bg-emerald-50 border-emerald-300 rounded-xl text-emerald-800">
                <span class="font-bold text-emerald-600">✓</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="p-6 space-y-4 bg-white border-b border-gray-100 shadow-sm">
            <div class="pb-2 border-b border-gray-100">
                <h2 class="text-sm font-bold tracking-wide text-gray-700 uppercase">Recherche de l'agent de la SONAGED
                </h2>
                <p class="text-xs text-gray-400">Saisissez le matricule, le nom ou le prénom pour charger le dossier.
                </p>
            </div>

            <div class="relative">
                <div class="flex space-x-2">
                    <div class="relative flex-1">
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchEmployees()"
                            placeholder="Ex: MTR-2024, Diop, Mamadou..."
                            class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            :disabled="employee !== null">

                        <button type="button" x-show="employee !== null" @click="resetEmployee()"
                            class="absolute right-3 top-2.5 text-xs text-red-500 hover:underline font-medium">
                            Changer d'agent
                        </button>
                    </div>
                </div>

                <div class="absolute left-0 right-0 z-50 mt-1 overflow-hidden bg-white border border-gray-200 divide-y divide-gray-100 rounded-lg shadow-xl"
                    x-show="searchResults.length > 0">
                    <template x-for="emp in searchResults" :key="emp.id">
                        <button type="button" @click="selectEmployee(emp)"
                            class="flex items-center justify-between w-full px-4 py-3 text-sm text-left transition hover:bg-emerald-50">
                            <div>
                                <span class="font-semibold text-gray-900" x-text="emp.prenom + ' ' + emp.nom"></span>
                                <span class="block text-xs text-gray-500"
                                    x-text="emp.emploi_occupe || 'Fonction non renseignée'"></span>
                            </div>
                            <span class="px-2 py-1 font-mono text-xs text-gray-600 bg-gray-100 rounded"
                                x-text="emp.matricule"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 p-4 border bg-emerald-50/50 border-emerald-200 rounded-xl sm:grid-cols-2"
                x-show="employee !== null" x-transition>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Matricule :</span>
                    <p class="text-sm font-bold text-gray-800" x-text="employee?.matricule"></p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Direction / Département
                        :</span>
                    <p class="text-sm font-semibold text-gray-800" x-text="employee?.direction || 'N/A'"></p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Zone d'affectation (UC)
                        :</span>
                    <p class="text-sm font-semibold text-gray-800" x-text="employee?.unite_communale || 'N/A'"></p>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-emerald-700">Poste actuel occupé :</span>
                    <p class="text-sm font-semibold text-gray-800" x-text="employee?.emploi_occupe || 'N/A'"></p>
                </div>
            </div>
        </div>

        <div class="p-12 text-center text-gray-400 bg-white border-b border-gray-100 rounded-b-xl"
            x-show="employee === null">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-sm">Veuillez rechercher et sélectionner un employé ci-dessus pour faire apparaître son
                questionnaire QHSE.</p>
        </div>

        <div x-show="employee !== null" x-transition>

            <div
                class="flex items-center justify-between px-6 py-3 text-xs font-medium text-gray-400 bg-white border-b border-gray-100 shadow-sm">
                <span :class="step === 1 ? 'text-emerald-600 font-bold' : 'text-gray-900'">1. Planning</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 2 ? 'text-emerald-600 font-bold' : (step > 2 ? 'text-gray-900' : '')">2. Charge
                    Physique</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 3 ? 'text-emerald-600 font-bold' : (step > 3 ? 'text-gray-900' : '')">3.
                    Environnement</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 4 ? 'text-emerald-600 font-bold' : (step > 4 ? 'text-gray-900' : '')">4.
                    Logistique EPI</span>
                <span class="w-12 h-px bg-gray-200"></span>
                <span :class="step === 5 ? 'text-emerald-600 font-bold' : ''">5. Clôture</span>
            </div>

            <form action="{{ route('qhse.store') }}" method="POST"
                class="p-6 space-y-8 bg-white shadow-sm rounded-b-xl sm:p-8">
                @csrf

                <input type="hidden" name="employee_id" :value="employee?.id">

                <div x-show="step === 1" class="space-y-6">
                    <div class="pb-2 border-b">
                        <h3 class="text-base font-bold text-gray-900">Complément d'identification & Horaires</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label
                                class="block mb-2 text-xs font-semibold tracking-wider text-gray-700 uppercase">Ancienneté
                                à ce poste</label>
                            <input type="text" name="anciennete" class="w-full text-sm border-gray-300 rounded-lg"
                                placeholder="Ex: 2 ans">
                        </div>
                        <div>
                            <label
                                class="block mb-2 text-xs font-semibold tracking-wider text-gray-700 uppercase">Activité
                                dominante *</label>
                            <select name="type_activite_dominante" class="w-full text-sm border-gray-300 rounded-lg">
                                <option value="Terrain">Terrain</option>
                                <option value="Bureau">Bureau</option>
                                <option value="Mixte">Mixte</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-semibold tracking-wider text-gray-700 uppercase">Horaire
                            de travail (Choix multiples) *</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['Jour', 'après midi', 'Nuit'] as $val)
                                <label
                                    class="flex items-center p-3 text-sm border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="horaire_travail[]" value="{{ $val }}"
                                        class="mr-2 rounded text-emerald-600"> En équipe de {{ ucfirst($val) }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="step === 2" class="space-y-6">
                    <div class="pb-2 border-b">
                        <h3 class="text-base font-bold text-gray-900">Contraintes Physiques & Ergonomiques</h3>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-800">Manutention et efforts physiques
                            (Choix multiples)</label>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach (['Port manuel de charges lourdes (> 15 kg)', 'Soulèvement fréquent de bacs / sacs / déchets en vrac', 'Poussée ou traction de conteneurs', 'Manipulation de déchets volumineux', 'Efforts intenses répétés'] as $eff)
                                <label
                                    class="border rounded-lg p-2.5 flex items-center cursor-pointer hover:bg-gray-50 text-xs text-gray-600">
                                    <input type="checkbox" name="manutention_efforts[]" value="{{ $eff }}"
                                        class="mr-3 rounded text-emerald-600"> {{ $eff }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block mb-2 text-xs font-semibold text-gray-700 uppercase">Fréquence de
                            manutention *</label>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach (['Rare', 'Occasionnelle', 'Fréquente', 'Permanente'] as $frq)
                                <label
                                    class="p-2 text-xs text-center bg-white border rounded-lg cursor-pointer hover:bg-gray-100"><input
                                        type="radio" name="frequence_manutention" value="{{ $frq }}"
                                        class="mr-1 text-emerald-600"> {{ $frq }}</label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-xs font-semibold text-gray-700 uppercase">Niveau de pénibilité
                            global ressenti (1 à 5) *</label>
                        <div class="grid grid-cols-5 gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label
                                    class="border rounded-lg p-2.5 text-center cursor-pointer hover:bg-emerald-50"><input
                                        type="radio" name="niveau_penibilite" value="{{ $i }}"
                                        class="block mx-auto mb-1 text-emerald-600"> <span
                                        class="text-sm font-bold">{{ $i }}</span></label>
                            @endfor
                        </div>
                    </div>
                </div>

                <div x-show="step === 3" class="space-y-6">
                    <div class="pb-2 border-b">
                        <h3 class="text-base font-bold text-gray-900">Environnement & Nuisances</h3>
                    </div>
                    <div>
                        <h4 class="mb-2 text-xs font-bold tracking-wider text-gray-400 uppercase">Nuisances Biologiques
                            & Chimiques</h4>
                        <div class="grid grid-cols-1 gap-2 text-xs sm:grid-cols-2">
                            @foreach (['Contact avec lixiviats', 'Poussières de déchets', 'Odeurs fortes / gaz de décharge', 'Risque de piqûres / coupures (objets tranchants)', 'Présence d’animaux nuisibles'] as $nui)
                                <label class="border rounded-lg p-2.5 flex items-center"><input type="checkbox"
                                        name="nuisances_chimiques_biologiques[]" value="{{ $nui }}"
                                        class="mr-2 rounded text-emerald-600"> {{ $nui }}</label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 text-xs font-semibold bg-gray-50 rounded-xl">
                        <span>Avez-vous déjà été témoin d’un accident sur site ? *</span>
                        <div class="space-x-4">
                            <label><input type="radio" name="temoin_accident" value="1"
                                    class="text-emerald-600"> Oui</label>
                            <label><input type="radio" name="temoin_accident" value="0"
                                    class="text-emerald-600"> Non</label>
                        </div>
                    </div>
                </div>

                <div x-show="step === 4" class="space-y-6">
                    <div class="pb-2 border-b">
                        <h3 class="text-base font-bold text-gray-900">Équipements de Protection Individuelle</h3>
                    </div>
                    @php $epis = ['Casquette', 'Gants anti-coupure', 'Bottes en caoutchouc', 'Chaussure de sécurité', 'Masque anti-poussière', 'Gilet haute visibilité']; @endphp
                    <div>
                        <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">EPI portés
                            quotidiennement</label>
                        <div class="grid grid-cols-2 gap-2 text-xs sm:grid-cols-3">
                            @foreach ($epis as $ep)
                                <label class="p-2 border rounded"><input type="checkbox"
                                        name="epi_utilises_quotidien[]" value="{{ $ep }}"
                                        class="mr-2 rounded text-emerald-600"> {{ $ep }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="step === 5" class="space-y-6">
                    <div class="pb-2 border-b">
                        <h3 class="text-base font-bold text-gray-900">Appréciation Globale & Soumission</h3>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Comment évaluez-vous le niveau de
                            risque à votre poste ? *</label>
                        <div class="grid grid-cols-4 gap-2 text-xs font-semibold text-center">
                            @foreach (['Faible', 'Modéré', 'Élevé', 'Très élevé'] as $ris)
                                <label class="p-3 border rounded-lg cursor-pointer hover:bg-gray-50"><input
                                        type="radio" name="niveau_risque_agent" value="{{ $ris }}"
                                        class="mr-1 text-emerald-600"> {{ $ris }}</label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-800">Suggestions de l'agent</label>
                        <textarea name="suggestions_amelioration" rows="4" class="w-full text-xs border-gray-300 rounded-lg"
                            placeholder="Saisir les remarques libres..."></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="button" x-show="step > 1" @click="step--"
                        class="px-4 py-2 text-xs font-medium text-gray-700 bg-white border rounded-lg shadow-sm hover:bg-gray-50">
                        Précédent
                    </button>
                    <div x-show="step === 1"></div>

                    <button type="button" x-show="step < 5" @click="step++"
                        class="px-4 py-2 text-xs font-medium text-white rounded-lg shadow-sm bg-emerald-600 hover:bg-emerald-700">
                        Suivant →
                    </button>

                    <button type="submit" x-show="step === 5"
                        class="px-5 py-2 text-xs font-bold text-white rounded-lg shadow-md bg-gradient-to-r from-emerald-600 to-teal-600">
                        Enregistrer la Fiche QHSE
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
