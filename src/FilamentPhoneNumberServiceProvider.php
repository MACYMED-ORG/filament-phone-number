<?php

namespace Macymed\FilamentPhoneNumber;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class FilamentPhoneNumberServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'filament-phone-number');

        $this->publishes([
            __DIR__ . '/Resources/views' => resource_path('views/vendor/filament-phone-number'),
        ], 'filament-phone-number-views');
    }

    public function register(): void
    {
        //
    }
}