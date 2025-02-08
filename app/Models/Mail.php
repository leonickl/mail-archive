<?php

namespace App\Models;

use App\People;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use PhpMimeMailParser\Parser;

/**
 * @property int $id
 * @property ?string $message_id
 * @property ?string $subject
 * @property ?Carbon $date
 * @property People $from
 * @property People $to
 * @property ?string $body_plain
 * @property ?string $body_html
 * @property string $eml_path
 */
class Mail extends Model
{
    public static function make(
        ?string $message_id,
        ?string $subject,
        ?Carbon $date,
        array $from,
        array $to,
        ?string $body_plain,
        ?string $body_html,
        string $eml_path,
    ): self
    {
        $mail = new self;

        $mail->message_id = $message_id;
        $mail->subject = $subject;
        $mail->date = $date;
        $mail->from = json_encode($from);
        $mail->to = json_encode($to);
        $mail->body_plain = $body_plain;
        $mail->body_html = $body_html;
        $mail->eml_path = $eml_path;

        return $mail;
    }

    public static function parse(string $path): self
    {
        $parser = (new Parser)->setText(file_get_contents(env('STORAGE_PATH') . '/' . $path));

        $date = $parser->getHeader('date');

        try {
            $carbon = Carbon::make($date);
        } catch (InvalidFormatException) {
            $carbon = null;
        }

        return self::make(
            message_id: $parser->getHeader('message-id') ?: null,
            subject: $parser->getHeader('subject') ?: null,
            date: $date === false ? null : $carbon,
            from: $parser->getAddresses('from'),
            to: $parser->getAddresses('to'),
            body_plain: $parser->getMessageBody(),
            body_html: $parser->getMessageBody('html'),
            eml_path: $path,
        );
    }

    public function tryToSave(): void
    {
        try {
            $this->save();
            echo 'saved ' . $this->message_id . PHP_EOL;
        } catch (UniqueConstraintViolationException) {
            echo 'skipping ' . $this->message_id . PHP_EOL;
        }
    }

    protected function casts(): array
    {
        return ['date' => 'datetime'];
    }

    protected function from(): Attribute
    {
        return Attribute::get(fn(string $from) => People::new($from));
    }

    protected function to(): Attribute
    {
        return Attribute::get(fn(string $to) => People::new($to));
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'date' => $this->date,
            'from' => $this->from?->string(),
            'from_long' => $this->from?->longString(),
            'to' => $this->to?->string(),
            'to_long' => $this->to?->longString(),
        ];
    }
}
