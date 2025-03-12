<?php

namespace Macymed\FilamentPhoneNumber\Components;


use Filament\Tables\Columns\TextColumn;
use Macymed\FilamentPhoneNumber\Helpers\CountryHelper;
use Macymed\FilamentPhoneNumber\Helpers\PhoneNumberHelper;


class PhoneNumberColumn extends TextColumn
{
    protected string $view = 'filament-phone-number::columns.phone-number-column';
    
    
    protected bool $showFlags = true;
    protected string $format = 'NATIONAL';
    
    public function showFlags(bool $showFlags = true): static
    {
        $this->showFlags = $showFlags;

        return $this;
    }
    
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }
    
    public function shouldShowFlags(): bool
    {
        return $this->showFlags;
    }
    
    public function getFormat(): string
    {
        return $this->format;
    }
    
    public function getPhoneInfo(string $state = null): ?array
    {
        if (empty($state)) {
            return null;
        }
        
        $info = PhoneNumberHelper::parseE164($state);
        
        if (!$info) {
            return [
                'number' => $state,
                'country_code' => null,
                'flag' => null,
            ];
        }
        
        return [
            'number' => $info['e164'],//$info['national_number'],
            'e164' => $info['e164'],
            'country_code' => $info['country_code'],
            'flag' => CountryHelper::getCountryFlag($info['country_code']),
        ];
    }
}