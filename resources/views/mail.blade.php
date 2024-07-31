@php /** @var App\Models\Mail $mail */ @endphp

<x-app>

    <div>
        <div>{{ $mail->date }}</div>
        <div>{{ $mail->subject }}</div>
    </div>

    <div>
        <div>{{ $mail->from?->string() }}</div>
        <div>{{ $mail->to?->string() }}</div>
    </div>

    <pre>{{ trim($mail->body_plain ?? '') }}</pre>

</x-app>
