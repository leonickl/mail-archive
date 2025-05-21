<?php

namespace App\Http\Controllers;

use App\Models\Mail;

class MailController extends Controller
{
    public function index()
    {
        $search = request('search');
        $limit = request()->integer('limit', 20);

        if ($search) {
            $ids = Mail::search($search)
                ->get()
                ->pluck('id');

            $mails = Mail::whereIn('id', $ids)
                ->orderByDesc('date')
                ->paginate($limit, columns: ['id', 'subject', 'date', 'from', 'to']);
        } else {
            $mails = Mail::query()
                ->orderByDesc('date')
                ->paginate($limit, columns: ['id', 'subject', 'date', 'from', 'to']);
        }

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
        return $mail->body_html;
    }

    public function plain(Mail $mail)
    {
        return $mail->body_plain;
    }
}
