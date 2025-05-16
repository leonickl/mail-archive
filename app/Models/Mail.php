<?php

namespace App\Models;

use App\People;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpMimeMailParser\Parser;

/**
 * @property int $id
 * @property string $eml_path
 * @property string $file
 * @property ?string $message_id
 * @property ?string $subject
 * @property ?Carbon $date
 * @property ?People $from
 * @property ?People $to
 * @property ?string $body_plain
 * @property ?string $body_html
 */
class Mail extends Model
{
    protected $fillable = ['eml_path', 'file'];

    public static function parse(string $path): self
    {
        $contents = Storage::disk('mails')->get($path);

        $parser = (new Parser)->setText($contents);

        $date = $parser->getHeader('date');

        try {
            $carbon = Carbon::make($date);
        } catch (InvalidFormatException) {
            $carbon = null;
        }

        $message_id = $parser->getHeader('message-id')
            ?: $date.'-'.utf8_encode($parser->getHeader('subject'));
        $subject = utf8_encode($parser->getHeader('subject')) ?: null;
        $date = $date === false ? null : $carbon;
        $from = $parser->getAddresses('from');
        $to = $parser->getAddresses('to');
        $body_plain = utf8_encode($parser->getMessageBody());
        $body_html = utf8_encode($parser->getMessageBody('html'));
        $eml_path = $path;

        dd();
    }

    protected function casts(): array
    {
        return ['date' => 'datetime'];
    }

    protected function from(): Attribute
    {
        return Attribute::get(fn (string $from) => People::new($from));
    }

    protected function to(): Attribute
    {
        return Attribute::get(fn (string $to) => People::new($to));
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
