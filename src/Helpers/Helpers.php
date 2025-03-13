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

class Helpers
{
    /**
     * Convertit une valeur de type mixed en une chaîne de caractères.
     *
     * Si la valeur est une chaîne, elle est retournée telle quelle.
     * Si la valeur est null, une chaîne vide est retournée.
     * Si la valeur est scalaire, elle est castée en string.
     *
     * @param mixed $mixed la valeur à convertir
     *
     * @return string la représentation sous forme de chaîne
     */
    public static function stringFromMixed(mixed $mixed): string
    {
        if (is_string($mixed)) {
            return $mixed;
        }

        if (is_null($mixed)) {
            return '';
        }

        if (is_scalar($mixed)) {
            return (string) $mixed;
        }

        if (is_array($mixed)) {
            $json = json_encode($mixed);

            return $json !== false ? $json : '';
        }

        if (is_object($mixed)) {
            $json = json_encode($mixed);

            return $json !== false ? $json : '';
        }

        return '';
    }

    /**
     * Convertit une valeur de type mixed en entier.
     *
     * Si la valeur est déjà un entier, elle est retournée telle quelle.
     * Si la valeur est numérique (string ou float), elle est convertie en int.
     * Sinon, 0 est retourné.
     *
     * @param mixed $value la valeur à convertir
     *
     * @return int la valeur convertie en entier
     */
    public static function intFromMixed(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return 0;
    }
}
