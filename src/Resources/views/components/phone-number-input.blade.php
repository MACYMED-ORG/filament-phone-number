<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{
            country: @js($getDefaultCountry()),
            phoneNumber: @js($getFormattedNumber()),
            state: $wire.entangle('{{ $getStatePath() }}'),
            selectedCountry: '{{ $getDefaultCountry() }}',
            phoneInput: '{{ $getFormattedNumber() }}',
            masks: {{ json_encode($getMasks()) }},
            countries: {{ json_encode($getCountries()) }},
            isOpen: false,
            search: '',
            filteredCountries: [],
            selectedFlag: @js(\App\Helpers\CountryHelper::getCountryFlag($getDefaultCountry())),
            
            init() {
                // Surveiller les changements de pays pour mettre à jour le masque
                this.$watch('country', () => {
                    this.updateMaskedInput();
                    // Mettre à jour le drapeau quand le pays change
                    this.selectedFlag = this.getCountryFlag(this.country);
                });
                
                // Initialiser les pays filtrés
                this.filteredCountries = Object.entries(this.countries).map(([code, data]) => ({
                    code,
                    name: data.name,
                    prefix: data.prefix
                }));
                
                // Surveiller les changements de recherche
                this.$watch('search', () => {
                    this.filterCountries();
                });
                
                // Fermer le dropdown si on clique en dehors
                document.addEventListener('click', (e) => {
                    if (!this.$el.contains(e.target)) {
                        this.isOpen = false;
                    }
                });
            },
            
            getCountryFlag(code) {
                // Cette fonction est simulée ici - le serveur génère réellement les drapeaux
                // Nous devons stocker la valeur HTML du drapeau
                return this.countries[code]?.flag || '';
            },
            
            filterCountries() {
                const searchTerm = this.search.toLowerCase();
                this.filteredCountries = Object.entries(this.countries)
                    .filter(([code, data]) => {
                        return data.name.toLowerCase().includes(searchTerm) || 
                               code.toLowerCase().includes(searchTerm) ||
                               data.prefix.includes(searchTerm);
                    })
                    .map(([code, data]) => ({
                        code,
                        name: data.name,
                        prefix: data.prefix
                    }));
            },
            
            selectCountry(code) {
                this.country = code;
                this.isOpen = false;
                this.search = '';
                this.updateMaskedInput();
                // Mettre à jour le drapeau lors de la sélection
                this.selectedFlag = this.getCountryFlag(code);
            },
            
            updateMaskedInput() {
                // Extraire les chiffres de l'entrée actuelle
                const digits = this.phoneNumber.replace(/\D/g, '');
                
                // Appliquer le nouveau masque
                const mask = this.masks[this.country] || '';
                if (!mask) {
                    this.phoneNumber = digits;
                    return;
                }
                
                let formatted = '';
                let maskIndex = 0;
                let digitIndex = 0;
                
                while (maskIndex < mask.length && digitIndex < digits.length) {
                    if (mask[maskIndex] === '#') {
                        formatted += digits[digitIndex];
                        digitIndex++;
                    } else {
                        formatted += mask[maskIndex];
                    }
                    maskIndex++;
                }
                
                this.phoneNumber = formatted;
                this.updateState();
            },
            
            updateState() {
                const prefix = this.countries[this.country]?.prefix || '';
                // Nettoyer le numéro des caractères non numériques pour le stockage
                const cleanNumber = this.phoneNumber.replace(/\D/g, '');
                
                if (cleanNumber) {
                    this.state = prefix + cleanNumber;
                } else {
                    this.state = '';
                }
            },
            
            formatInput(event) {
                const mask = this.masks[this.country] || '';
                if (!mask) return;
                
                const input = event.target;
                const value = input.value.replace(/\D/g, '');
                let formatted = '';
                let maskIndex = 0;
                let valueIndex = 0;
                
                while (maskIndex < mask.length && valueIndex < value.length) {
                    if (mask[maskIndex] === '#') {
                        formatted += value[valueIndex];
                        valueIndex++;
                    } else {
                        formatted += mask[maskIndex];
                    }
                    maskIndex++;
                }
                
                // Mettre à jour avec la valeur formatée
                this.phoneNumber = formatted;
                this.updateState();
            }
        }"
        class="flex space-x-2"
    >
        <!-- Country Selector with Search -->
        <div class="flex-shrink-0 w-42 relative">
            <div 
                @click="isOpen = !isOpen" 
                class="w-full h-10 border-gray-300 rounded-lg shadow-sm focus:border-primary-500 flex items-center justify-between px-3 cursor-pointer bg-white"
                :class="{ 'ring-1 ring-primary-500 border-primary-500': isOpen }"
            >
                <span>
                    @if ($shouldShowFlags())
                        <!-- Ici, on utilise la variable Alpine pour le drapeau -->
                        <span class="mr-2" x-html="selectedFlag"></span>
                    @endif
                    <span x-text="countries[country] ?  ' (' + countries[country].prefix + ')' : ''"></span>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" :class="{ 'transform rotate-180': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <!-- Dropdown -->
            <div 
                x-show="isOpen" 
                x-cloak
                class="absolute z-10 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                style="width: 300px;" 
                @click.away="isOpen = false"
            >
                <!-- Search Input -->
                <div class="p-2 sticky top-0 bg-white border-b border-gray-200">
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Rechercher un pays..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        @click.stop
                    >
                </div>
                
                <!-- Countries List -->
                <div>
                    @foreach ($getCountries() as $code => $countryData)
                        <div 
                            x-show="filteredCountries.some(c => c.code === '{{ $code }}')"
                            @click="selectCountry('{{ $code }}')" 
                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center"
                        >
                            @if ($shouldShowFlags())
                                <!-- On passe le drapeau dans le modèle Alpine countries -->
                                @php
                                    $countryData['flag'] = \App\Helpers\CountryHelper::getCountryFlag($code);
                                @endphp
                                <span class="mr-2">{!! $countryData['flag'] !!}</span>
                            @endif
                            <span>{{ $countryData['name'] }} ({{ $countryData['prefix'] }})</span>
                        </div>
                    @endforeach
                    
                    <!-- No results message -->
                    <div x-show="filteredCountries.length === 0" class="px-3 py-2 text-gray-500 italic">
                        Aucun pays ne correspond à votre recherche
                    </div>
                </div>
            </div>
        </div>

        <!-- Phone Number Input -->
        <div class="flex-grow ">
            <input
                x-model="phoneNumber"
                @input="formatInput($event)"
                autocomplete="tel"
                :placeholder="masks[country] || ''"
                type="tel"
                id="{{ $getId() }}"
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                {!! $isDisabled() ? 'disabled' : null !!}
                {!! $isRequired() ? 'required' : null !!}
                {{ $getExtraInputAttributeBag()->class([
                    'block w-full h-10 transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500',
                    'border-gray-300' => ! $errors->has($getStatePath()),
                    'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                ]) }}
            />
        </div>
    </div>
</x-dynamic-component>