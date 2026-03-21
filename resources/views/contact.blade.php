@extends('layouts.app')

@section('title', 'Kapcsolat')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Kapcsolatfelvétel</h1>
        <a href="{{ route('user-dashboard') }}" class="btn btn-outline-secondary">Vissza a dashboardra</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="full_name">Teljes név</label>
                    <input
                        id="full_name"
                        type="text"
                        class="form-control"
                        value="{{ $user->first_name }} {{ $user->last_name }}"
                        disabled
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control"
                        value="{{ $user->email }}"
                        disabled
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label" for="phone_no">Telefonszám</label>
                    <input
                        id="phone_no"
                        type="text"
                        class="form-control"
                        value="{{ $user->phone_no }}"
                        disabled
                    >
                </div>

                <div class="mb-4">
                    <label class="form-label" for="message">Üzenet</label>
                    <textarea
                        id="message"
                        name="message"
                        class="form-control"
                        rows="6"
                        placeholder="Írd ide az üzeneted..."
                        required
                    >{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Üzenet küldése</button>
            </form>
        </div>
    </div>
@endsection
