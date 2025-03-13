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
    <div 
        x-data="{
            country: @js($getDefaultCountry()),
            phoneNumber: @js($getFormattedNumber()),
            state: $wire.entangle('{{ $getStatePath() }}'),
            selectedCountry: '{{ $getDefaultCountry() }}',
            phoneInput: '{{ $getFormattedNumber() }}',
            masks: {{ json_encode($getMasks()) }},
            countries: {{ json_encode($getCountries()) }},
            isOpen: false,
            search: '',
            filteredCountries: Object.entries({{ json_encode($getCountries()) }})
                .map(([code, data]) => ({ code, name: data.name, prefix: data.prefix })),
            selectedFlag: @js(\Macymed\FilamentPhoneNumber\Helpers\CountryHelper::getCountryFlag($getDefaultCountry())),
            
            getCountryFlag(code) {
                // Flag côté serveur (SVG ou emoji)
                return this.countries[code]?.flag || '';
            },
            getFallbackFlag(code) {
                // Fallback local : <img> vers le SVG
                return `<img src='{{ asset("vendor/filament-macymed-phone-number/images/flags") }}/${code.toLowerCase()}.svg' alt='Drapeau ${code}' class='w-6 h-6'>`;
            },
            emojiSupported() {
                try {
                    const canvas = document.createElement('canvas');
                    canvas.width = canvas.height = 16;
                    const ctx = canvas.getContext('2d');
                    ctx.textBaseline = 'top';
                    ctx.font = '16px Arial';
                    ctx.fillText(this.getCountryFlag(this.country), 0, 0);
                    return canvas.toDataURL().indexOf('data:image/png') === 0;
                } catch (e) {
                    return false;
                }
            },
            filterCountries() {
                const term = this.search.toLowerCase();
                this.filteredCountries = Object.entries(this.countries)
                    .filter(([code, data]) => {
                        return data.name.toLowerCase().includes(term)
                            || code.toLowerCase().includes(term)
                            || data.prefix.includes(term);
                    })
                    .map(([code, data]) => ({ code, name: data.name, prefix: data.prefix }));
            },
            selectCountry(code) {
                this.country = code;
                this.isOpen = false;
                this.search = '';
                this.updateMaskedInput();
                this.selectedFlag = this.emojiSupported() 
                    ? this.getCountryFlag(code)
                    : this.getFallbackFlag(code);
            },
            updateMaskedInput() {
                const digits = this.phoneNumber.replace(/\D/g, '');
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
                        formatted += digits[digitIndex++];
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
                const cleanNumber = this.phoneNumber.replace(/\D/g, '');
                this.state = cleanNumber ? prefix + cleanNumber : '';
            },
            formatInput() {
                this.updateMaskedInput();
            }
        }"
        x-init="$watch('search', value => { filterCountries(); })"
        class="flex space-x-2"
    >
        <!-- Sélecteur de pays -->
        <div class="flex-shrink-0 w-42 relative">
            <div 
                @click="isOpen = !isOpen" 
                class="w-full h-10 border-gray-300 rounded-lg shadow-sm focus:border-primary-500 
                       flex items-center justify-between px-3 cursor-pointer bg-white"
                :class="{ 'ring-1 ring-primary-500 border-primary-500': isOpen }"
            >
                <span>
                    @if($shouldShowFlags())
                        <span 
                            class="inline-block w-6 h-4 overflow-hidden align-middle mr-2"
                            x-html="selectedFlag"
                        ></span>
                    @endif
                    <span x-text="countries[country] ? (' (' + countries[country].prefix + ')') : ''"></span>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-5 w-5 text-gray-400"
                     :class="{ 'transform rotate-180': isOpen }"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M19 9l-7 7-7-7" 
                    />
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
                <!-- Barre de recherche -->
                <div class="p-2 sticky top-0 bg-white border-b border-gray-200">
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Rechercher un pays..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md 
                               focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        @click.stop
                    >
                </div>
                
                <!-- Liste des pays -->
                <div>
                    <template x-for="countryItem in filteredCountries" :key="countryItem.code">
                        <div 
                            @click="selectCountry(countryItem.code)" 
                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center"
                        >
                        @if($shouldShowFlags())
                                <span 
                                    class="inline-block w-6 h-4 overflow-hidden align-middle mr-2"
                                    x-html="emojiSupported() 
                                        ? getCountryFlag(countryItem.code) 
                                        : getFallbackFlag(countryItem.code)"
                                ></span>
                        @endif
                            <span x-text="countryItem.name + ' (' + countryItem.prefix + ')'"></span>
                        </div>
                    </template>
                    
                    <div x-show="filteredCountries.length === 0" class="px-3 py-2 text-gray-500 italic">
                        Aucun pays ne correspond à votre recherche
                    </div>
                </div>
            </div>
        </div>

        <!-- Champ de saisie du numéro -->
        <div class="flex-grow">
            <input
                x-model="phoneNumber"
                @input="formatInput($event)"
                autocomplete="tel"
                :placeholder="masks[country] || ''"
                type="tel"
                id="{{ $getId() }}"
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : '' !!}
                {!! $isDisabled() ? 'disabled' : '' !!}
                {!! $isRequired() ? 'required' : '' !!}
                {{ $getExtraInputAttributeBag()->class([
                    'block w-full h-10 transition duration-75 rounded-lg shadow-sm 
                     focus:border-primary-500 focus:ring-1 focus:ring-primary-500',
                    'border-gray-300' => ! $errors->has($getStatePath()),
                    'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                ]) }}
            />
        </div>
    </div>
</x-dynamic-component>
