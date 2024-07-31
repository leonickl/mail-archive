@php /** @var Illuminate\Contracts\Pagination\LengthAwarePaginator $mails */ @endphp

<x-app title="Mails">

    <div class="action">
        <div>
            @if($mails->currentPage() > 1)
                <div><a href="{{ $mails->url(1) }}">&lt;&lt;</a></div>
                <div><a href="{{ $mails->previousPageUrl() }}">&lt;</a></div>
            @endif

            <div>page {{ $mails->currentPage() }} of {{ $mails->lastPage() }}</div>

            @if($mails->hasMorePages())
                <div><a href="{{ $mails->nextPageUrl() }}">&gt;</a></div>
                <div><a href="{{ $mails->url($mails->lastPage()) }}">&gt;&gt;</a></div>
            @endif
        </div>

        <form action="{{ request()->fullUrl() }}">
            <input class="styled" type="text" name="search">
            <input class="styled" type="submit" value="Search">
        </form>
    </div>

    <table>

        <tr>
            <th></th>
            <th>Subject</th>
            <th>Date</th>
            <th>From</th>
            <th>To</th>
        </tr>

        @php /** @var App\Models\Mail $mail */ @endphp
        @forelse($mails as $mail)
            <tr>
                <th><a href="{{ route('mail', $mail) }}" target="_blank">{{ $mail->id }}</a></th>
                <td>{{ $mail->subject ?? '---' }}</td>
                <td>{{ $mail->date?->format('d.m.Y') ?? '---' }}</td>
                <td>{{ $mail->from?->string() ?? '---' }}</td>
                <td>{{ $mail->to?->string() ?? '---' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">
                    <div class="center">No mails found.</div>
                </td>
            </tr>
        @endforelse

        <tr>
            <th></th>
            <th>Subject</th>
            <th>Date</th>
            <th>From</th>
            <th>To</th>
        </tr>

    </table>

</x-app>
