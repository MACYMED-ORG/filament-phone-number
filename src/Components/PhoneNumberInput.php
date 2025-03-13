<?php
/**
 *  Copyright since 2007 Macymed and Contributors
 *
 *  NOTICE OF LICENSE
 *
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.md.
 *  It is also available through the world-wide-web at this URL:
 *  https://opensource.org/licenses/OSL-3.0
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to info@macymed.fr so we can send you a copy immediately.
 *
 *  DISCLAIMER
 *
 *  Do not edit or add to this file if you wish to upgrade Macymed to newer
 *  versions in the future. 
 *
 *  @author    Macymed and Contributors <contact@macymed.fr>
 *  @copyright Since 2007 Macymed and Contributors
 *  @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
/**
 *  Copyright since 2007 Macymed and Contributors
 *
 *  NOTICE OF LICENSE
 *
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.md.
 *  It is also available through the world-wide-web at this URL:
 *  https://opensource.org/licenses/OSL-3.0
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to info@macymed.fr so we can send you a copy immediately.
 *
 *  DISCLAIMER
 *
 *  Do not edit or add to this file if you wish to upgrade Macymed to newer
 *  versions in the future.
 *
 *  @author    Macymed and Contributors <contact@macymed.fr>
 *  @copyright Since 2007 Macymed and Contributors
 *  @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Macymed\FilamentPhoneNumber\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Macymed\FilamentPhoneNumber\Helpers\CountryHelper;
use Macymed\FilamentPhoneNumber\Helpers\Helpers;
use Macymed\FilamentPhoneNumber\Helpers\PhoneNumberHelper;

/**
 * @property ?string $state
 */
class PhoneNumberInput extends TextInput
{
    protected string $view = 'filament-macymed-phone-number::components.phone-number-input';

    /**
     * @var array<string, array<string, string>>
     */
    protected array $countries = [];

    /**
     * @var array<string, string>
     */
    protected array $masks = [];

    protected string $defaultCountry = 'FR';
    protected bool $saveAsE164 = true;
    protected bool $showFlags = true;

    // Déclaration explicite de la propriété d'état
    protected ?string $state = null;

    /**
     * @return array<string, array<string, string>>
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return array<string, string>
     */
    public function getMasks(): array
    {
        return $this->masks;
    }

    public function getDefaultCountry(): string
    {
        return $this->detectCountryFromState() ?? $this->defaultCountry;
    }

    /**
     * @param array<string, array<string, string>> $countries
     */
    public function countries(array $countries): static
    {
        $this->countries = $countries;

        return $this;
    }

    /**
     * @param array<string, string> $masks
     */
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

        // Récupération des valeurs par défaut depuis la configuration (en forçant les types)
        $defaultCountry = Helpers::stringFromMixed(config('filament-macymed-phone-number.default_country', 'FR'));
        $showFlags = (bool) config('filament-macymed-phone-number.show_flags', true);
        $saveAsE164 = (bool) config('filament-macymed-phone-number.save_as_e164', true);

        $this->defaultCountry($defaultCountry)
            ->showFlags($showFlags)
            ->saveAsE164($saveAsE164);

        $this->afterStateHydrated(function (Field $component, $state): void {
            if (!empty($state) && $this->shouldSaveAsE164()) {
                $parsedNumber = PhoneNumberHelper::parseE164(Helpers::stringFromMixed($state));
                if ($parsedNumber) {
                    // Utilisation d'un setter ou affectation directe, selon l'API du composant
                    $this->state = Helpers::stringFromMixed($parsedNumber['national_number']);
                    $this->defaultCountry = Helpers::stringFromMixed($parsedNumber['country_code']);
                }
            }
        });

        $this->dehydrateStateUsing(function (Field $component, $state) {
            if (empty($state)) {
                return null;
            }
            if ($this->shouldSaveAsE164()) {
                $country = $this->getDefaultCountry();

                return PhoneNumberHelper::formatToE164(Helpers::stringFromMixed($state), $country);
            }

            return $state;
        });
    }

    protected function initializeDefaultCountries(): void
    {
        if (empty($this->countries)) {
            $this->countries = CountryHelper::getAllCountries();
        }
    }

    protected function initializeDefaultMasks(): void
    {
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

        return PhoneNumberHelper::detectCountry(Helpers::stringFromMixed($state), $this->countries);
    }

    public function getFormattedNumber(): string
    {
        $state = $this->getState();
        if (empty($state)) {
            return '';
        }

        $country = $this->getDefaultCountry();
        $prefix = isset($this->countries[$country]['prefix'])
            ? (string) $this->countries[$country]['prefix']
            : '';

        // Si le numéro commence déjà par le préfixe, on le retire et on applique le masque
        if ($prefix && is_string($state) && str_starts_with($state, $prefix)) {
            $number = substr((string) $state, strlen($prefix));
            $mask = isset($this->masks[$country]) ? (string) $this->masks[$country] : '';
            if ($mask) {
                return (string) PhoneNumberHelper::applyMask($number, $mask);
            }

            return $number;
        }

        return Helpers::stringFromMixed($state);
    }
}
