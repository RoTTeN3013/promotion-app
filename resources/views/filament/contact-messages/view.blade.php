<div class="space-y-4">
    <div>
        <h3 class="text-sm font-semibold text-gray-700">Felhasználó</h3>
        <p class="text-sm text-gray-900">
            {{ $record->user?->first_name }} {{ $record->user?->last_name }}
            @if($record->user?->email)
                ({{ $record->user->email }})
            @endif
        </p>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-gray-700">Eredeti üzenet</h3>
        <div class="rounded-md border border-gray-200 p-3 text-sm text-gray-900 whitespace-pre-line">
            {{ $record->message }}
        </div>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-gray-700">Válasz</h3>
        @if($record->answer?->message)
            <div class="rounded-md border border-gray-200 p-3 text-sm text-gray-900 whitespace-pre-line">
                {{ $record->answer->message }}
            </div>
        @else
            <p class="text-sm text-gray-500">Még nincs válasz erre az üzenetre.</p>
        @endif
    </div>
</div>
