@extends('layouts.app')

@section('title', 'Profilom')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Profilom</h1>
        <a href="{{ route('user-dashboard') }}" class="btn btn-outline-secondary">Vissza a dashboardra</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('user-profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">Keresztnév</label>
                        <input id="first_name" type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Vezetéknév</label>
                        <input id="last_name" type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail cím</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone_no" class="form-label">Telefonszám</label>
                    <input id="phone_no" type="text" name="phone_no" class="form-control" value="{{ old('phone_no', $user->phone_no) }}" required>
                </div>

                <div class="mb-3">
                    <label for="bank_account_no" class="form-label">Bankszámlaszám</label>
                    <input id="bank_account_no" type="text" name="bank_account_no" class="form-control" value="{{ old('bank_account_no', $user->bank_account_no) }}" required>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Új jelszó (opcionális)</label>
                        <input id="password" type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Új jelszó megerősítése</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Mentés</button>
            </form>
        </div>
    </div>
@endsection
