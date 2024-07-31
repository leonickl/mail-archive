@php /** @var App\Models\Mail $mail */ @endphp

<x-app title="{{ $mail->subject }}">

    <div class="data-row">
        <div>{{ $mail->date }}</div>
        <x-sep/>
        <div>{{ $mail->subject }}</div>
        <x-sep/>
        <div>{{ $mail->from?->string() }}</div>
        <x-sep/>
        <div>{{ $mail->to?->string() }}</div>
    </div>

    @unless(trim($mail->body_plain) === '')
        <pre>{{ trim($mail->body_plain ?? '') }}</pre>
    @endunless

    @unless(trim($mail->body_html) === '')
        <iframe class="body-html" src="{{ route('mail-body', $mail) }}"></iframe>
    @endunless

</x-app>
