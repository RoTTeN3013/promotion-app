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
                    <a href="#" class="btn btn-primary">Kapcsolatfelvétel</a>
                </div>
            </div>
        </div>
    </div>
@endsection
