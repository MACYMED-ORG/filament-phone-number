<?php

namespace Macymed\FilamentPhoneNumber\Components;


use Filament\Forms\Components\Field;
use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Filament\Forms\Components\TextInput;
use Macymed\FilamentPhoneNumber\Helpers\CountryHelper;
use Macymed\FilamentPhoneNumber\Helpers\PhoneNumberHelper;






class PhoneNumberInput extends TextInput
{
    protected string $view = 'filament-phone-number::components.phone-number-input';
    
    protected array $countries = [];
    protected array $masks = [];
    protected string $defaultCountry = 'FR';
    protected bool $saveAsE164 = true;

    protected bool $showFlags = true;
    

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function getMasks(): array
    {
        return $this->masks;
    }

    public function getDefaultCountry(): string
    {
        return $this->detectCountryFromState() ?? $this->defaultCountry;
    }

    public function countries(array $countries): static
    {
        $this->countries = $countries;
        
        return $this;
    }

    public function masks(array $masks): static
    {
        $this->masks = $masks;
        
        return $this;
    }

    public function defaultCountry(string $country): static
    {
        $this->defaultCountry = $country;
        
        return $this;
    }
    
    public function saveAsE164(bool $saveAsE164 = true): static
    {
        $this->saveAsE164 = $saveAsE164;

        return $this;
    }

    public function shouldShowFlags(): bool
    {
        return $this->showFlags;
    }
    
    public function shouldSaveAsE164(): bool
    {
        return $this->saveAsE164;
    }
    
    public function showFlags(bool $showFlags = true): static
    {
        $this->showFlags = $showFlags;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->initializeDefaultCountries();
        $this->initializeDefaultMasks();
        
        $this->afterStateHydrated(function (Field $component, $state) {
            if (!empty($state) && $this->shouldSaveAsE164()) {
                $parsedNumber = PhoneNumberHelper::parseE164($state);
                
                if ($parsedNumber) {
                    $this->state = $parsedNumber['national_number'];
                    $this->defaultCountry = $parsedNumber['country_code'];
                }
            }
        });
        
        $this->dehydrateStateUsing(function (Field $component, $state) {
            if (empty($state)) {
                return null;
            }
            
            if ($this->shouldSaveAsE164()) {
                $country = $this->getDefaultCountry();
                return PhoneNumberHelper::formatToE164($state, $country);
            }
            
            return $state;
        });
    }

    protected function initializeDefaultCountries(): void
    {
        // Si les pays ne sont pas déjà définis
        if (empty($this->countries)) {
            $this->countries = CountryHelper::getAllCountries();
        }
    }

    protected function initializeDefaultMasks(): void
    {
        // Si les masques ne sont pas déjà définis
        if (empty($this->masks)) {
            $this->masks = CountryHelper::getDefaultMasks();
        }
    }
    
    protected function detectCountryFromState(): ?string
    {
        $state = $this->getState();
        
        if (empty($state)) {
            return null;
        }
        
        return PhoneNumberHelper::detectCountry($state, $this->countries);
    }
    
    public function getFormattedNumber(): string
    {
        $state = $this->getState();
        
        if (empty($state)) {
            return '';
        }
        
        $country = $this->getDefaultCountry();
        $prefix = $this->countries[$country]['prefix'] ?? '';
        
        // Si le numéro commence déjà par le préfixe, l'afficher sans préfixe
        if ($prefix && str_starts_with($state, $prefix)) {
            $number = substr($state, strlen($prefix));
            
            // Appliquer le masque
            $mask = $this->masks[$country] ?? '';
            if ($mask) {
                return PhoneNumberHelper::applyMask($number, $mask);
            }
            
            return $number;
        }
        
        return $state;
    }
}