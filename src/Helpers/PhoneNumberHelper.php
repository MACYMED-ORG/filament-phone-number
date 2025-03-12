<?php

namespace Macymed\FilamentPhoneNumber\Helpers;


use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class PhoneNumberHelper
{
    /**
     * Format a phone number to E164 format
     *
     * @param string $phoneNumber
     * @param string $countryCode
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
     * @return array|null
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
                $digitIndex++;
            } else {
                $result .= $mask[$maskIndex];
            }
            $maskIndex++;
        }
        
        return $result;
    }

    /**
     * Detect country from phone number
     *
     * @param string $phoneNumber
     * @param array $countries
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