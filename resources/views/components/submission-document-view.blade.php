@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@if($getRecord() && $getRecord()->doc_img_path)
    @php
        $record = $getRecord();
        $imageUrl = null;
        $imageExists = false;

        if (Str::startsWith($record->doc_img_path, ['http://', 'https://'])) {
            $imageUrl = $record->doc_img_path;
            $imageExists = true;
        } else {
            $normalizedPath = ltrim(preg_replace('#^(?:public/|storage/)#', '', $record->doc_img_path), '/');
            $imageExists = Storage::disk('public')->exists($normalizedPath);
            $imageUrl = Storage::disk('public')->url($normalizedPath);
        }
    @endphp

    @if($imageUrl && $imageExists)
        <a href="{{ $imageUrl }}" target="_blank" rel="noopener" class="text-primary">
            Fájl megtekintése
        </a>
    @else
        <span class="text-danger">A fájl nem található</span>
    @endif
@else
    <span class="text-muted">Nincs fájl feltöltve</span>
@endif
