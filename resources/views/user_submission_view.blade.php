@extends('layouts.app')

@section('title', 'Feltoltes megtekintese')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Feltoltes #{{ $submission->id }}</h1>
        <a href="{{ route('user-submissions') }}" class="btn btn-outline-secondary">Vissza</a>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Promo</dt>
                <dd class="col-sm-9">{{ $submission->promotion?->name ?? '-' }}</dd>

                <dt class="col-sm-3">Statusz</dt>
                <dd class="col-sm-9">{{ $submission->status ?? '-' }}</dd>

                <dt class="col-sm-3">AP szam</dt>
                <dd class="col-sm-9">{{ $submission->ap_no ?? '-' }}</dd>

                <dt class="col-sm-3">Vasarlas datuma</dt>
                <dd class="col-sm-9">{{ $submission->purchase_date?->format('Y-m-d') ?? '-' }}</dd>

                <dt class="col-sm-3">Dokumentum</dt>
                <dd class="col-sm-9">{{ $submission->doc_img_path ?? '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection
