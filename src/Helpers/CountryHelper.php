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

class CountryHelper
{
    /**
     * Get all countries with their names in local language, codes and prefixes
     *
     * @return array<string, array<string,string>>
     */
    public static function getAllCountries(): array
    {
        $countries = [
            'AD' => ['name' => 'Andorra', 'code' => 'AD', 'prefix' => '+376'],
            'AE' => ['name' => 'الإمارات العربية المتحدة', 'code' => 'AE', 'prefix' => '+971'],
            'AF' => ['name' => 'افغانستان', 'code' => 'AF', 'prefix' => '+93'],
            'AG' => ['name' => 'Antigua and Barbuda', 'code' => 'AG', 'prefix' => '+1268'],
            'AI' => ['name' => 'Anguilla', 'code' => 'AI', 'prefix' => '+1264'],
            'AL' => ['name' => 'Shqipëria', 'code' => 'AL', 'prefix' => '+355'],
            'AM' => ['name' => 'Հայաստան', 'code' => 'AM', 'prefix' => '+374'],
            'AO' => ['name' => 'Angola', 'code' => 'AO', 'prefix' => '+244'],
            'AR' => ['name' => 'Argentina', 'code' => 'AR', 'prefix' => '+54'],
            'AT' => ['name' => 'Österreich', 'code' => 'AT', 'prefix' => '+43'],
            'AU' => ['name' => 'Australia', 'code' => 'AU', 'prefix' => '+61'],
            'AW' => ['name' => 'Aruba', 'code' => 'AW', 'prefix' => '+297'],
            'AZ' => ['name' => 'Azərbaycan', 'code' => 'AZ', 'prefix' => '+994'],
            'BA' => ['name' => 'Bosna i Hercegovina', 'code' => 'BA', 'prefix' => '+387'],
            'BB' => ['name' => 'Barbados', 'code' => 'BB', 'prefix' => '+1246'],
            'BD' => ['name' => 'বাংলাদেশ', 'code' => 'BD', 'prefix' => '+880'],
            'BE' => ['name' => 'België/Belgique', 'code' => 'BE', 'prefix' => '+32'],
            'BF' => ['name' => 'Burkina Faso', 'code' => 'BF', 'prefix' => '+226'],
            'BG' => ['name' => 'България', 'code' => 'BG', 'prefix' => '+359'],
            'BH' => ['name' => '‏البحرين', 'code' => 'BH', 'prefix' => '+973'],
            'BI' => ['name' => 'Burundi', 'code' => 'BI', 'prefix' => '+257'],
            'BJ' => ['name' => 'Bénin', 'code' => 'BJ', 'prefix' => '+229'],
            'BL' => ['name' => 'Saint-Barthélemy', 'code' => 'BL', 'prefix' => '+590'],
            'BM' => ['name' => 'Bermuda', 'code' => 'BM', 'prefix' => '+1441'],
            'BN' => ['name' => 'Brunei Darussalam', 'code' => 'BN', 'prefix' => '+673'],
            'BO' => ['name' => 'Bolivia', 'code' => 'BO', 'prefix' => '+591'],
            'BR' => ['name' => 'Brasil', 'code' => 'BR', 'prefix' => '+55'],
            'BS' => ['name' => 'Bahamas', 'code' => 'BS', 'prefix' => '+1242'],
            'BT' => ['name' => 'འབྲུག་ཡུལ', 'code' => 'BT', 'prefix' => '+975'],
            'BW' => ['name' => 'Botswana', 'code' => 'BW', 'prefix' => '+267'],
            'BY' => ['name' => 'Беларусь', 'code' => 'BY', 'prefix' => '+375'],
            'BZ' => ['name' => 'Belize', 'code' => 'BZ', 'prefix' => '+501'],
            'CA' => ['name' => 'Canada', 'code' => 'CA', 'prefix' => '+1'],
            'CH' => ['name' => 'Schweiz/Suisse/Svizzera', 'code' => 'CH', 'prefix' => '+41'],
            'CL' => ['name' => 'Chile', 'code' => 'CL', 'prefix' => '+56'],
            'CN' => ['name' => '中国', 'code' => 'CN', 'prefix' => '+86'],
            'CO' => ['name' => 'Colombia', 'code' => 'CO', 'prefix' => '+57'],
            'CR' => ['name' => 'Costa Rica', 'code' => 'CR', 'prefix' => '+506'],
            'CU' => ['name' => 'Cuba', 'code' => 'CU', 'prefix' => '+53'],
            'CV' => ['name' => 'Cabo Verde', 'code' => 'CV', 'prefix' => '+238'],
            'CY' => ['name' => 'Κύπρος', 'code' => 'CY', 'prefix' => '+357'],
            'CZ' => ['name' => 'Česká republika', 'code' => 'CZ', 'prefix' => '+420'],
            'DE' => ['name' => 'Deutschland', 'code' => 'DE', 'prefix' => '+49'],
            'DK' => ['name' => 'Danmark', 'code' => 'DK', 'prefix' => '+45'],
            'DZ' => ['name' => 'الجزائر', 'code' => 'DZ', 'prefix' => '+213'],
            'EC' => ['name' => 'Ecuador', 'code' => 'EC', 'prefix' => '+593'],
            'EE' => ['name' => 'Eesti', 'code' => 'EE', 'prefix' => '+372'],
            'EG' => ['name' => 'مصر', 'code' => 'EG', 'prefix' => '+20'],
            'ES' => ['name' => 'España', 'code' => 'ES', 'prefix' => '+34'],
            'FI' => ['name' => 'Suomi', 'code' => 'FI', 'prefix' => '+358'],
            'FR' => ['name' => 'France', 'code' => 'FR', 'prefix' => '+33'],
            'GB' => ['name' => 'United Kingdom', 'code' => 'GB', 'prefix' => '+44'],
            'GR' => ['name' => 'Ελλάδα', 'code' => 'GR', 'prefix' => '+30'],
            'HK' => ['name' => '香港', 'code' => 'HK', 'prefix' => '+852'],
            'HR' => ['name' => 'Hrvatska', 'code' => 'HR', 'prefix' => '+385'],
            'HU' => ['name' => 'Magyarország', 'code' => 'HU', 'prefix' => '+36'],
            'ID' => ['name' => 'Indonesia', 'code' => 'ID', 'prefix' => '+62'],
            'IE' => ['name' => 'Éire', 'code' => 'IE', 'prefix' => '+353'],
            'IL' => ['name' => 'ישראל', 'code' => 'IL', 'prefix' => '+972'],
            'IN' => ['name' => 'भारत', 'code' => 'IN', 'prefix' => '+91'],
            'IQ' => ['name' => 'العراق', 'code' => 'IQ', 'prefix' => '+964'],
            'IR' => ['name' => 'ایران', 'code' => 'IR', 'prefix' => '+98'],
            'IS' => ['name' => 'Ísland', 'code' => 'IS', 'prefix' => '+354'],
            'IT' => ['name' => 'Italia', 'code' => 'IT', 'prefix' => '+39'],
            'JM' => ['name' => 'Jamaica', 'code' => 'JM', 'prefix' => '+1876'],
            'JO' => ['name' => 'الأردن', 'code' => 'JO', 'prefix' => '+962'],
            'JP' => ['name' => '日本', 'code' => 'JP', 'prefix' => '+81'],
            'KE' => ['name' => 'Kenya', 'code' => 'KE', 'prefix' => '+254'],
            'KR' => ['name' => '대한민국', 'code' => 'KR', 'prefix' => '+82'],
            'KW' => ['name' => 'الكويت', 'code' => 'KW', 'prefix' => '+965'],
            'LB' => ['name' => 'لبنان', 'code' => 'LB', 'prefix' => '+961'],
            'LT' => ['name' => 'Lietuva', 'code' => 'LT', 'prefix' => '+370'],
            'LU' => ['name' => 'Lëtzebuerg', 'code' => 'LU', 'prefix' => '+352'],
            'LV' => ['name' => 'Latvija', 'code' => 'LV', 'prefix' => '+371'],
            'MA' => ['name' => 'المغرب', 'code' => 'MA', 'prefix' => '+212'],
            'MC' => ['name' => 'Monaco', 'code' => 'MC', 'prefix' => '+377'],
            'MD' => ['name' => 'Moldova', 'code' => 'MD', 'prefix' => '+373'],
            'ME' => ['name' => 'Crna Gora', 'code' => 'ME', 'prefix' => '+382'],
            'MG' => ['name' => 'Madagasikara', 'code' => 'MG', 'prefix' => '+261'],
            'MK' => ['name' => 'Северна Македонија', 'code' => 'MK', 'prefix' => '+389'],
            'ML' => ['name' => 'Mali', 'code' => 'ML', 'prefix' => '+223'],
            'MM' => ['name' => 'မြန်မာ', 'code' => 'MM', 'prefix' => '+95'],
            'MX' => ['name' => 'México', 'code' => 'MX', 'prefix' => '+52'],
            'MY' => ['name' => 'Malaysia', 'code' => 'MY', 'prefix' => '+60'],
            'NG' => ['name' => 'Nigeria', 'code' => 'NG', 'prefix' => '+234'],
            'NL' => ['name' => 'Nederland', 'code' => 'NL', 'prefix' => '+31'],
            'NO' => ['name' => 'Norge', 'code' => 'NO', 'prefix' => '+47'],
            'NZ' => ['name' => 'New Zealand', 'code' => 'NZ', 'prefix' => '+64'],
            'PA' => ['name' => 'Panamá', 'code' => 'PA', 'prefix' => '+507'],
            'PE' => ['name' => 'Perú', 'code' => 'PE', 'prefix' => '+51'],
            'PH' => ['name' => 'Pilipinas', 'code' => 'PH', 'prefix' => '+63'],
            'PK' => ['name' => 'پاکستان', 'code' => 'PK', 'prefix' => '+92'],
            'PL' => ['name' => 'Polska', 'code' => 'PL', 'prefix' => '+48'],
            'PT' => ['name' => 'Portugal', 'code' => 'PT', 'prefix' => '+351'],
            'QA' => ['name' => 'قطر', 'code' => 'QA', 'prefix' => '+974'],
            'RO' => ['name' => 'România', 'code' => 'RO', 'prefix' => '+40'],
            'RS' => ['name' => 'Србија', 'code' => 'RS', 'prefix' => '+381'],
            'RU' => ['name' => 'Россия', 'code' => 'RU', 'prefix' => '+7'],
            'SA' => ['name' => 'المملكة العربية السعودية', 'code' => 'SA', 'prefix' => '+966'],
            'SE' => ['name' => 'Sverige', 'code' => 'SE', 'prefix' => '+46'],
            'SG' => ['name' => 'Singapore', 'code' => 'SG', 'prefix' => '+65'],
            'SI' => ['name' => 'Slovenija', 'code' => 'SI', 'prefix' => '+386'],
            'SK' => ['name' => 'Slovensko', 'code' => 'SK', 'prefix' => '+421'],
            'TH' => ['name' => 'ประเทศไทย', 'code' => 'TH', 'prefix' => '+66'],
            'TN' => ['name' => 'تونس', 'code' => 'TN', 'prefix' => '+216'],
            'TR' => ['name' => 'Türkiye', 'code' => 'TR', 'prefix' => '+90'],
            'TW' => ['name' => '台灣', 'code' => 'TW', 'prefix' => '+886'],
            'UA' => ['name' => 'Україна', 'code' => 'UA', 'prefix' => '+380'],
            'US' => ['name' => 'United States', 'code' => 'US', 'prefix' => '+1'],
            'UY' => ['name' => 'Uruguay', 'code' => 'UY', 'prefix' => '+598'],
            'VE' => ['name' => 'Venezuela', 'code' => 'VE', 'prefix' => '+58'],
            'VN' => ['name' => 'Việt Nam', 'code' => 'VN', 'prefix' => '+84'],
            'ZA' => ['name' => 'South Africa', 'code' => 'ZA', 'prefix' => '+27'],
        ];
        foreach ($countries as $code => $country) {
            $country['flag'] = CountryHelper::getCountryFlag($code);
            $countries[$code] = $country;
        }

        return $countries;
    }

    /**
     * Get default masks for phone number formatting
     *
     * @return array<string,string>
     */
    public static function getDefaultMasks(): array
    {
        return [
            'AD' => '### ###',
            'AE' => '## ### ####',
            'AF' => '## ### ####',
            'AG' => '### ####',
            'AI' => '### ####',
            'AL' => '## ### ####',
            'AM' => '## ### ###',
            'AO' => '### ### ###',
            'AR' => '## #### ####',
            'AT' => '### ### ####',
            'AU' => '### ### ###',
            'AW' => '### ####',
            'AZ' => '## ### ## ##',
            'BA' => '## ### ###',
            'BB' => '### ####',
            'BD' => '## ### ####',
            'BE' => '### ### ###',
            'BF' => '## ## ## ##',
            'BG' => '### ### ###',
            'BH' => '#### ####',
            'BI' => '## ## ## ##',
            'BJ' => '## ## ## ##',
            'BL' => '### ## ## ##',
            'BM' => '### ####',
            'BN' => '### ####',
            'BO' => '#### ####',
            'BR' => '## #### ####',
            'BS' => '### ####',
            'BT' => '## ### ###',
            'BW' => '## ### ###',
            'BY' => '## ### ## ##',
            'BZ' => '### ####',
            'CA' => '### ### ####',
            'CH' => '### ### ## ##',
            'CL' => '# #### ####',
            'CN' => '### #### ####',
            'CO' => '### ### ####',
            'CR' => '#### ####',
            'CU' => '# ### ####',
            'CV' => '### ## ##',
            'CY' => '## ### ###',
            'CZ' => '### ### ###',
            'DE' => '### ## ########',
            'DK' => '## ## ## ##',
            'DZ' => '### ## ## ##',
            'EC' => '## ### ####',
            'EE' => '#### ####',
            'EG' => '## #### ####',
            'ES' => '### ### ###',
            'FI' => '## ### ## ##',
            'FR' => '## ## ## ## ##',
            'GB' => '#### ######',
            'GR' => '### ### ####',
            'HK' => '#### ####',
            'HR' => '## ### ####',
            'HU' => '## ### ####',
            'ID' => '### ### ####',
            'IE' => '## ### ####',
            'IL' => '### ### ####',
            'IN' => '#### ### ###',
            'IQ' => '### ### ####',
            'IR' => '### ### ####',
            'IS' => '### ####',
            'IT' => '### ### ####',
            'JM' => '### ####',
            'JO' => '# #### ####',
            'JP' => '### ### ####',
            'KE' => '### ### ###',
            'KR' => '### ### ####',
            'KW' => '#### ####',
            'LB' => '## ### ###',
            'LT' => '### #####',
            'LU' => '### ### ###',
            'LV' => '#### ####',
            'MA' => '## ### ####',
            'MC' => '## ## ## ##',
            'MD' => '### ## ###',
            'ME' => '## ### ###',
            'MG' => '## ## ### ##',
            'MK' => '## ### ###',
            'ML' => '## ## ## ##',
            'MM' => '# ### ####',
            'MX' => '### ### ####',
            'MY' => '## ### ####',
            'NG' => '### ### ####',
            'NL' => '# ### ### ##',
            'NO' => '### ## ###',
            'NZ' => '### ### ####',
            'PA' => '### ####',
            'PE' => '### ### ###',
            'PH' => '### ### ####',
            'PK' => '### ### ####',
            'PL' => '### ### ###',
            'PT' => '### ### ###',
            'QA' => '#### ####',
            'RO' => '### ### ###',
            'RS' => '## ### ####',
            'RU' => '### ### ## ##',
            'SA' => '### ### ####',
            'SE' => '## ### ## ##',
            'SG' => '#### ####',
            'SI' => '## ### ###',
            'SK' => '### ### ###',
            'TH' => '# ### ####',
            'TN' => '## ### ###',
            'TR' => '### ### ## ##',
            'TW' => '#### ####',
            'UA' => '## ### ## ##',
            'US' => '(###) ###-####',
            'UY' => '# ### ## ##',
            'VE' => '### ### ####',
            'VN' => '## ### ## ##',
            'ZA' => '## ### ####',
        ];
    }

    /**
     * Get country flag emoji
     *
     * @param string $countryCode
     *
     * @return string
     */
    public static function getCountryFlag(string $countryCode): string
    {
        $countryCode = strtoupper($countryCode);
        // Convert each letter to the corresponding regional indicator symbol emoji
        $flag = implode('', array_map(function ($char) {
            return mb_chr(ord($char) + 127397);
        }, str_split($countryCode)));

        return $flag;
    }

    /**
     * Get country name in local language
     *
     * @param string $countryCode
     *
     * @return string|null
     */
    public static function getCountryName(string $countryCode): ?string
    {
        $countries = self::getAllCountries();

        return $countries[$countryCode]['name'] ?? null;
    }
}
