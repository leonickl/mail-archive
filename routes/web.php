<?php

use App\Models\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Number;

Route::get('/', function () {
    return view('main', [
        'total' => Illuminate\Support\Number::forHumans(App\Models\Mail::query()->count()),
        'perYear' => DB::table('mails')
            ->selectRaw('strftime("%Y", date) as year, count(*) as count')
            ->groupByRaw('strftime("%Y", date)')
            ->get()
            ->map(fn(object $data) => '<div><b>' . ($data->year ?? '---') . ':</b> ' . Number::format($data->count, locale: 'de') . '</div>')
            ->join(''),
    ]);
});

Route::get('/mails', function () {
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

    return view('mails', ['mails' => $builder->orderByDesc('date')->paginate(50)]);
})->name('mails');

Route::get('/mails/{mail}', function (Mail $mail) {
    return view('mail', ['mail' => $mail]);
})->name('mail');

Route::get('/mails/{mail}/body', function (Mail $mail) {
    return view('mail-body', ['mail' => $mail]);
})->name('mail-body');
