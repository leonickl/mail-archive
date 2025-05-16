<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::controller(MailController::class)->group(function () {
    Route::get('api/mails', 'index')->name('mails');
    Route::get('api/mails/{mail}', 'show')->name('mail');
    Route::get('api/mails/{mail}/html', 'html')->name('mail');
    Route::get('api/mails/{mail}/plain', 'plain')->name('mail');
});
