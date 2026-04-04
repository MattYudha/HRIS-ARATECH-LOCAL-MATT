<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register QrCode facade alias
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('QrCode', \SimpleSoftwareIO\QrCode\Facades\QrCode::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register missing signature verification route as a fallback since routes/web.php is read-only
        \Illuminate\Support\Facades\Route::middleware(['web'])
            ->get('verify-signature', [\App\Http\Controllers\SignatureController::class, 'publicVerify'])
            ->name('signatures.public-verify');

        // Fix for route collision: specific POST route for verification
        \Illuminate\Support\Facades\Route::middleware(['web', 'auth', 'role:HR Administrator,Super Admin'])
            ->post('signature-approve/{signature}', [\App\Http\Controllers\SignatureController::class, 'verify'])
            ->name('signatures.verify.fixed');
    }
}
