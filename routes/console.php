<?php

use App\Models\Mail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('import {year?}', function (?string $year = null) {
    $path = $year === null ? 'mails' : 'mails/' . $year;

    if (Storage::directoryMissing($path)) {
        $this->error('directory not found');
    }

    collect(Storage::allFiles($path))
        ->each(function (string $file) {
            $this->line($file);
            return Mail::parse(storage_path('app/' . $file));
        });
});
