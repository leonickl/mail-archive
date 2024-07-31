<?php

use App\Models\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $builder = Mail::query();

    if (request()->exists('search') && trim(request('search')) !== '') {
        $search = strtolower(trim(request('search')));

        $builder = $builder->orWhereRaw('lower(`subject`) like ?', ['%' . $search . '%'])
            ->orWhereRaw('strftime("%d.%m.%Y", `date`) like ?', ['%' . $search . '%'])
            ->orWhereRaw('lower(`body_plain`) like ?', ['%' . $search . '%'])
            ->orWhereRaw('lower(`date`) like ?', ['%' . $search . '%'])
            ->orWhereRaw('lower(`from`) like ?', ['%' . $search . '%'])
            ->orWhereRaw('lower(`to`) like ?', ['%' . $search . '%']);
    }

    return view('mails', ['mails' => $builder->orderByDesc('date')->paginate(100)]);
})->name('mails');

Route::get('/{mail}', function (Mail $mail) {
    return view('mail', ['mail' => $mail]);
})->name('mail');
