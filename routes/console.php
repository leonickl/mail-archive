<?php

use App\Models\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('import', function () {
    if (Storage::disk('files')->directoryMissing('mails')) {
        $this->error('directory not found');
    }

    collect(Storage::disk('files')->allFiles('mails'))
        ->filter(fn(string $file) => str_ends_with($file, '.eml'))
        ->each(function (string $file) {
            Mail::parse($file)->tryToSave();
        });
});
