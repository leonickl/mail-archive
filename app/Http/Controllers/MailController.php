<?php

namespace App\Http\Controllers;

use App\Models\Mail;

class MailController extends Controller
{
    public function index()
    {
        $mails = Mail::query()
            ->orderByDesc('date')
            ->paginate(20);

        return response()->json($mails, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
        ], JSON_UNESCAPED_UNICODE);
    }

    public function show(Mail $mail)
    {
        return $mail;
    }

    public function html(Mail $mail)
    {
        return ($mail->body_html);
    }

    public function plain(Mail $mail)
    {
        return $mail->body_plain;
    }
}
