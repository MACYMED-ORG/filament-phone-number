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

namespace Macymed\FilamentPhoneNumber\Helpers;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberHelper
{
    /**
     * Format a phone number to E164 format
     *
     * @param string $phoneNumber
     * @param string $countryCode
     *
     * @return string|null
     */
    public static function formatToE164(?string $phoneNumber, string $countryCode = 'FR'): ?string
    {
        if (empty($phoneNumber)) {
            return null;
        }

        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($phoneNumber, $countryCode);

            if ($phoneUtil->isValidNumber($numberProto)) {
                return $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
            }

            return $phoneNumber;
        } catch (NumberParseException $e) {
            return $phoneNumber;
        }
    }

    /**
     * Parse an E164 formatted phone number
     *
     * @param string $phoneNumber
     *
     * @return array<string, mixed>|null
     */
    public static function parseE164(?string $phoneNumber): ?array
    {
        if (empty($phoneNumber)) {
            return null;
        }

        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($phoneNumber, null);

            if ($phoneUtil->isValidNumber($numberProto)) {
                $countryCode = $phoneUtil->getRegionCodeForNumber($numberProto);
                $nationalNumber = $phoneUtil->format($numberProto, PhoneNumberFormat::NATIONAL);

                return [
                    'country_code' => $countryCode,
                    'national_number' => $nationalNumber,
                    'e164' => $phoneNumber,
                ];
            }

            return null;
        } catch (NumberParseException $e) {
            return null;
        }
    }

    /**
     * Apply a mask to a phone number
     *
     * @param string $phoneNumber
     * @param string $mask
     *
     * @return string
     */
    public static function applyMask(string $phoneNumber, string $mask): string
    {
        // Extraire seulement les chiffres
        $digits = preg_replace('/\D/', '', $phoneNumber);

        if (empty($digits) || empty($mask)) {
            return $phoneNumber;
        }

        $result = '';
        $maskIndex = 0;
        $digitIndex = 0;

        while ($maskIndex < strlen($mask) && $digitIndex < strlen($digits)) {
            if ($mask[$maskIndex] === '#') {
                $result .= $digits[$digitIndex];
                ++$digitIndex;
            } else {
                $result .= $mask[$maskIndex];
            }
            ++$maskIndex;
        }

        return $result;
    }

    /**
     * Detect country from phone number
     *
     * @param string $phoneNumber
     * @param array<string,array<string,string>> $countries
     *
     * @return string|null
     */
    public static function detectCountry(?string $phoneNumber, array $countries): ?string
    {
        if (empty($phoneNumber)) {
            return null;
        }

        // Try to detect with libphonenumber
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($phoneNumber, null);

            if ($phoneUtil->isValidNumber($numberProto)) {
                $region = $phoneUtil->getRegionCodeForNumber($numberProto);
                if ($region && array_key_exists($region, $countries)) {
                    return $region;
                }
            }
        } catch (NumberParseException $e) {
            // Ignore parsing errors
        }

        // Manually check prefixes

        foreach ($countries as $code => $country) {
            $prefix = $country['prefix'] ?? '';
            if ($prefix && str_starts_with($phoneNumber, $prefix)) {
                return $code;
            }
        }

        return null;
    }
}
