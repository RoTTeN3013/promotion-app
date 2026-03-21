@extends('layouts.app')

@section('title', 'Dashboard - Promotion App')

@section('content')
    <h1>Helló {{ Auth::user()->first_name }}</h1>
    <p class="lead">Itt tekintheted át és kezelheted a promóciókkal kapcsolatos tevékenységeidet.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profilom</h5>
                    <p class="card-text">Személyes adatok megtekintése és kezelése.</p>
                    <a href="#" class="btn btn-primary">Megtekintés</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Feltöltések</h5>
                    <p class="card-text">Összes eddigi feltöltésed promóciókhoz.</p>
                    <a href="{{ route('user-submissions') }}" class="btn btn-primary">Megtekintés</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kapcsolat</h5>
                    <p class="card-text">Szükséged van segítségre? Lépj kapcsolatba a támogatással.</p>
                    <a href="{{ route('contact.create') }}" class="btn btn-primary">Kapcsolatfelvétel</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Aktuális promóciók</h5>
                    <p class="card-text">Tekintsd meg az aktuális promóciókat.</p>
                    @if(isset($activePromotions) && $activePromotions->count() > 0)
                        @foreach($activePromotions as $promotion)
                            <div class="mb-2">
                                <h6>{{ $promotion->name }}</h6>
                                <p>Promóció vége: {{ \Carbon\Carbon::parse($promotion->date_to)->format('Y-m-d') }}</p>
                                <a href="{{ route('promotion.show', $promotion->id) }}" class="btn btn-sm btn-secondary">Részletek</a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Jelenleg nincs aktív promóció.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
