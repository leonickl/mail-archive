<?php

use App\Models\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Number;
use App\Http\Controllers\MailController;

Route::controller(MailController::class)->group(function() {
    Route::get('api/mails', 'index')->name('mails');
    Route::get('api/mails/{mail}', 'show')->name('mail');
});
