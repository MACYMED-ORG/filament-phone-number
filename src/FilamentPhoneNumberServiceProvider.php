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
namespace Macymed\FilamentPhoneNumber;

use Illuminate\Support\ServiceProvider;

class FilamentPhoneNumberServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Chargement des vues avec un préfixe unique
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'filament-macymed-phone-number');

        // Publication des vues avec un chemin de destination unique
        $this->publishes([
            __DIR__ . '/Resources/views' => resource_path('views/vendor/filament-macymed-phone-number'),
        ], 'filament-macymed-phone-number-views');

        // Publication de la configuration
        $this->publishes([
            __DIR__ . '/config/filament-macymed-phone-number.php' => config_path('filament-macymed-phone-number.php'),
        ], 'filament-macymed-phone-number-config');
    }

    public function register(): void
    {
        // Fusion de la configuration
        $this->mergeConfigFrom(
            __DIR__ . '/config/filament-macymed-phone-number.php', 'filament-macymed-phone-number'
        );
    }
}
