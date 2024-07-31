<?php

namespace App\Models;

use App\People;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use PhpMimeMailParser\Parser;

/**
 * @property int $id
 * @property ?string $subject
 * @property ?Carbon $date
 * @property ?string $from
 * @property ?string $to
 * @property ?string $body_plain
 * @property ?string $body_html
 */
class Mail extends Model
{
    public static function make(
        ?string $subject,
        ?Carbon $date,
        ?string $from,
        ?string $to,
        ?string $body_plain,
        ?string $body_html,
    ): self
    {
        $mail = new self;

        $mail->subject = $subject;
        $mail->date = $date;
        $mail->from = $from;
        $mail->to = $to;
        $mail->body_plain = $body_plain;
        $mail->body_html = $body_html;

        $mail->save();

        return $mail;
    }

    public static function parse(string $path): self
    {
        $parser = (new Parser)->setText(file_get_contents($path));

        $date = $parser->getHeader('date');
        $from = $parser->getHeader('from');
        $to = $parser->getHeader('to');

        try {
            $carbon = Carbon::make($date);
        } catch (InvalidFormatException) {
            $carbon = null;
        }

        return self::make(
            subject: $parser->getHeader('subject'),
            date: $date === false ? null : $carbon,
            from: $from === false ? null : $from,
            to: $to === false ? null : $to,
            body_plain: $parser->getMessageBody(),
            body_html: $parser->getMessageBody('html'),
        );
    }

    protected function casts(): array
    {
        return ['date' => 'datetime'];
    }

    protected function from(): Attribute
    {
        return Attribute::get(fn(?string $from) => $from === null ? null : People::new($from));
    }

    protected function to(): Attribute
    {
        return Attribute::get(fn(?string $to) => $to === null ? null : People::new($to));
    }
}
