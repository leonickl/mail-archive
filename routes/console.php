<?php

use App\Models\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('import', function () {
    $files = Storage::disk('mails')->allFiles();

    foreach($files as $file) {
        if(Str::endsWith($file, '.eml')) {
            Mail::parse($file)->tryToSave();
        }
    }
});
