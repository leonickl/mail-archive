<?php

namespace App\Models;

use App\People;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use PhpMimeMailParser\Parser;

use function GuzzleHttp\json_encode;

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

    public function parse(): self
    {
        $parser = (new Parser)->setText($this->file);

        $date = $parser->getHeader('date');

        try {
            $carbon = Carbon::make($date);
        } catch (InvalidFormatException) {
            $carbon = null;
        }

        $this->message_id = $parser->getHeader('message-id')
            ?: $date.'-'.mb_convert_encoding($parser->getHeader('subject'), 'utf-8');
        $this->subject = mb_convert_encoding($parser->getHeader('subject'), 'utf-8') ?: null;
        $this->date = $date === false ? null : $carbon;
        $this->from = json_encode($parser->getAddresses('from'));
        $this->to = json_encode($parser->getAddresses('to'));
        $this->body_plain = mb_convert_encoding($parser->getMessageBody(), 'utf-8');
        $this->body_html = mb_convert_encoding($parser->getMessageBody('html'), 'utf-8');

        return $this;
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
