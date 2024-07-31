<?php

use App\Models\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('import', function () {
    if (Storage::directoryMissing('mails')) {
        $this->error('directory not found');
    }

    collect(Storage::allFiles('mails'))
        ->filter(fn(string $file) => str_ends_with($file, '.eml'))
        ->each(function (string $file) {
            Mail::parse(storage_path('app/' . $file))->tryToSave();
        });
});
