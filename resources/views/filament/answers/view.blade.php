<div class="space-y-4">
    <div>
        <h3 class="text-sm font-semibold text-gray-700">Admin</h3>
        <p class="text-sm text-gray-900">{{ $record->admin?->username ?? 'Ismeretlen admin' }}</p>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-gray-700">Kapcsolati üzenet</h3>
        @if($record->contactMessage?->message)
            <div class="rounded-md border border-gray-200 p-3 text-sm text-gray-900 whitespace-pre-line">
                {{ $record->contactMessage->message }}
            </div>
        @else
            <p class="text-sm text-gray-500">A kapcsolódó üzenet nem található.</p>
        @endif
    </div>

    <div>
        <h3 class="text-sm font-semibold text-gray-700">Válasz</h3>
        <div class="rounded-md border border-gray-200 p-3 text-sm text-gray-900 whitespace-pre-line">
            {{ $record->message }}
        </div>
    </div>
</div>
