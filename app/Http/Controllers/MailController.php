<?php

namespace App\Http\Controllers;

use App\Models\Mail;

class MailController extends Controller
{
    public function index()
    {
        return Mail::query()
            ->orderByDesc('date')
            ->paginate(50);
    }

    public function show(Mail $mail)
    {
        return $mail;
    }
}
