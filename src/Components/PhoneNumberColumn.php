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
namespace Macymed\FilamentPhoneNumber\Components;

use Filament\Tables\Columns\TextColumn;
use Macymed\FilamentPhoneNumber\Helpers\CountryHelper;
use Macymed\FilamentPhoneNumber\Helpers\Helpers;
use Macymed\FilamentPhoneNumber\Helpers\PhoneNumberHelper;

class PhoneNumberColumn extends TextColumn
{
    protected string $view = 'filament-macymed-phone-number::columns.phone-number-column';

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

    /**
     * Retourne les informations du numéro de téléphone.
     *
     * @param string|null $state
     *
     * @return array{number: string, e164: string, country_code: ?string, flag: ?string}|null
     */
    public function getPhoneInfo(string $state = null): ?array
    {
        if (empty($state)) {
            return null;
        }

        $info = PhoneNumberHelper::parseE164($state);

        if (!$info) {
            return [
                'number' => $state,
                'e164' => '',
                'country_code' => null,
                'flag' => null,
            ];
        }

        // Récupération des options de configuration en forçant le type
        $saveAsE164 = (bool) config('filament-macymed-phone-number.save_as_e164', true);
        $defaultFormat = Helpers::stringFromMixed(config('filament-macymed-phone-number.default_format', 'NATIONAL'));

        // Déterminer le format à utiliser en fonction de la configuration
        $formattedNumber = $saveAsE164
            ? $info['e164']
            : ($defaultFormat === 'NATIONAL' ? $info['national_number'] : $info['e164']);

        return [
            'number' => Helpers::stringFromMixed($formattedNumber),
            'e164' => Helpers::stringFromMixed($info['e164']),
            'country_code' => Helpers::stringFromMixed($info['country_code']),
            'flag' => $info['country_code'] !== null
                ? CountryHelper::getCountryFlag(Helpers::stringFromMixed($info['country_code']))
                : null,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Récupérer le format et le paramétrage d'affichage par défaut depuis la configuration, en forçant les types
        $defaultFormat = Helpers::stringFromMixed(config('filament-macymed-phone-number.default_format', 'NATIONAL'));
        $showFlags = (bool) config('filament-macymed-phone-number.show_flags', true);

        $this->format($defaultFormat)
            ->showFlags($showFlags);
    }
}
