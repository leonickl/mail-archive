<?php

use App\Models\Mail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('import', function () {
    if (Storage::directoryMissing('mails')) {
        $this->error('directory not found');
    }

    collect(Storage::allFiles('mails'))
        ->each(function (string $file) {
            Mail::parse(storage_path('app/' . $file))->tryToSave();
        });
});
