@extends('layouts.app')

@section('title', 'Promóció részletek')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ $promotion->name }}</h1>
        <a href="{{ route('user-dashboard') }}" class="btn btn-outline-secondary">Vissza a dashboardra</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Promóció információk</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Promóció kezdete:</strong>
                    <div>{{ \Carbon\Carbon::parse($promotion->date_from)->format('Y-m-d') }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Promóció vége:</strong>
                    <div>{{ \Carbon\Carbon::parse($promotion->date_to)->format('Y-m-d') }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Feltöltési időszak kezdete:</strong>
                    <div>{{ \Carbon\Carbon::parse($promotion->upload_from)->format('Y-m-d') }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Feltöltési időszak vége:</strong>
                    <div>{{ \Carbon\Carbon::parse($promotion->upload_to)->format('Y-m-d') }}</div>
                </div>

        

                <div class="col-md-6">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Résztvevő termékek</h5>

            @if($promotion->promotionItems->count() > 0)
                <ul class="list-group">
                    @foreach($promotion->promotionItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $item->product?->name ?? 'Ismeretlen termék' }}</span>
                            @if($item->product)
                                <span class="badge bg-light text-dark">{{ number_format($item->product->price, 0, ',', ' ') }} Ft</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mb-0">Ehhez a promócióhoz még nincs termék hozzárendelve.</p>
            @endif
        </div>
    </div>

    <div>
        <a href="{{ route('create-user-submission') }}" class="btn btn-primary">Új feltöltés indítása</a>
    </div>
@endsection
