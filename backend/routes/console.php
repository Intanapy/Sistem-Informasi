<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('istore:about', function () {
    $this->comment('iStore API siap digunakan setelah konfigurasi database dan migrasi.');
})->purpose('Tampilkan informasi singkat backend iStore');
