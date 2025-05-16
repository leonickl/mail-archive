<?php

use App\Models\Mail;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('import', function () {
    $files = Storage::disk('mails')->allFiles();

    foreach ($files as $eml_path) {
        if (Str::endsWith($eml_path, '.eml')) {
            $file = Storage::disk('mails')->get($eml_path);

            $file = mb_convert_encoding($file,'utf-8');

            try {
                $mail = Mail::create(compact('eml_path', 'file'));

                echo 'saved '.$eml_path.PHP_EOL;
            } catch (UniqueConstraintViolationException) {
                echo 'skipping '.$eml_path.PHP_EOL;
            } catch (Exception $e) {
                echo 'error for ' . $eml_path . PHP_EOL;
                
                throw $e;
            }
        }
    }
});
