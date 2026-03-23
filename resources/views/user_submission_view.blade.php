@extends('layouts.app')

@php
    use App\Helpers\SubmissionStatusHelper;
    use Illuminate\Support\Str;
@endphp

@section('title', 'Feltöltés megtekintése')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Feltöltés #{{ $submission->id }}</h1>
        <a href="{{ route('user-submissions') }}" class="btn btn-outline-secondary">Vissza</a>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Promo</dt>
                <dd class="col-sm-9">{{ $submission->promotion?->name ?? '-' }}</dd>

                <dt class="col-sm-3">Statusz</dt>
                <dd class="col-sm-9">{{ SubmissionStatusHelper::label($submission->status) }}</dd>

                @if($submission->status === 'need_data' && $submission->message)
                    <dt class="col-sm-3">Megjegyzés</dt>
                    <dd class="col-sm-9">
                        <div class="alert alert-warning mb-0">
                            {{ $submission->message }}
                        </div>
                    </dd>
                @endif

                @if($submission->status === 'need_data')
                    <dt class="col-sm-3"></dt>
                    <dd class="col-sm-9">
                        <div class="alert alert-info">
                            <strong>⚠️ Fontos:</strong> Kérjük, frissítse a szükséges adatokat a profilján, majd kattintson az alábbi "Frissítve" gombra, hogy a feltöltés felülvizsgálatra kerüljön.
                        </div>
                    </dd>
                @endif

                <dt class="col-sm-3">Termékek</dt>
                <dd class="col-sm-9">
                    @if($submission->items)
                        <ul class="mb-0">
                            @foreach($submission->items as $item)
                                <li>{{ $item['name'] }} ({{ $item['price'] }} Ft)</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </dd>

                <dt class="col-sm-3">AP szám</dt>
                <dd class="col-sm-9">{{ $submission->ap_no ?? '-' }}</dd>

                <dt class="col-sm-3">Vásárlás dátuma</dt>
                <dd class="col-sm-9">{{ $submission->purchase_date?->format('Y-m-d') ?? '-' }}</dd>

                <dt class="col-sm-3">Dokumentum</dt>
                <dd class="col-sm-9">
                    @php
                        $imageUrl = null;

                        if ($submission->doc_img_path) {
                            if (Str::startsWith($submission->doc_img_path, ['http://', 'https://'])) {
                                $imageUrl = $submission->doc_img_path;
                            } else {
                                $normalizedPath = ltrim((string) preg_replace('#^(?:public/|storage/)#', '', str_replace('\\\\', '/', $submission->doc_img_path)), '/');
                                $imageUrl = asset('storage/' . $normalizedPath);
                            }
                        }
                    @endphp

                    @if($imageUrl)
                        <a href="{{ $imageUrl }}" target="_blank" rel="noopener">
                            <img src="{{ $imageUrl }}" alt="Feltoltott dokumentum" class="img-thumbnail" style="max-width: 360px; height: auto;">
                        </a>
                    @elseif($submission->doc_img_path)
                        <span class="text-danger">A kép URL nem állítható elő.</span>
                    @else
                        -
                    @endif
                </dd>
            </dl>
        </div>

        @if($submission->status === 'need_data')
            <div class="card-footer bg-light">
                <form action="{{ route('mark-updated-user-submission', $submission) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan szeretnéd megjelölni a feltöltést frissítettként?');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Frissítve
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
