<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Daftarkan layanan aplikasi tambahan di sini jika diperlukan.
    }

    public function boot(): void
    {
        // Pengaturan global aplikasi ditempatkan di sini.
    }
}
