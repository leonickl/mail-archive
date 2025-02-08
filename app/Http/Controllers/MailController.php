<?php

namespace App\Http\Controllers;

use App\Models\Mail;

class MailController extends Controller
{
    public function index()
    {
        return Mail::query()
            ->orderByDesc('date')
            ->paginate(20);
    }

    public function show(Mail $mail)
    {
        return $mail;
    }

    public function html(Mail $mail)
    {
        return $mail->body_html;
    }

    public function plain(Mail $mail)
    {
        return $mail->body_plain;
    }
}
