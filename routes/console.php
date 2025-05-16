<?php

use App\Models\Mail;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('import', function () {
    $files = Storage::disk('mails')->allFiles();

    foreach ($files as $file) {
        if (Str::endsWith($file, '.eml')) {
            try {
                Mail::create(compact('file'));
                echo 'saved '.$this->message_id.PHP_EOL;
            } catch (UniqueConstraintViolationException) {
                echo 'skipping '.$this->message_id.PHP_EOL;
            }
        }
    }
});
