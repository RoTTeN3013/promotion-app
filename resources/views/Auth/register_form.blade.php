@extends('layouts.auth')

@section('title', 'Regisztrációs űrlap')
@section('heading', 'Regisztrációs űrlap')

@section('content')
	<form method="POST" action="{{ route('register.user') }}">
		@csrf

		<div class="form-group">
			<label for="first_name">Keresztnév</label>
			<input type="text" id="first_name" name="first_name" placeholder="Add meg a keresztneved" value="{{ old('first_name') }}" required>
		</div>

		<div class="form-group">
			<label for="last_name">Vezetéknév</label>
			<input type="text" id="last_name" name="last_name" placeholder="Add meg a vezetékneved" value="{{ old('last_name') }}" required>
		</div>

		<div class="form-group">
			<label for="email">Email cím</label>
			<input type="email" id="email" name="email" placeholder="Add meg az email címed" value="{{ old('email') }}" required>
		</div>

		<div class="form-group">
			<label for="phone_no">Telefonszám</label>
			<input type="tel" id="phone_no" name="phone_no" placeholder="Add meg a telefonszámodat (pl: +36301111111, 06301111111)" value="{{ old('phone_no') }}" required>
		</div>

		<div class="form-group">
			<label for="bank_account_no">Számlaszám</label>
			<input type="text" id="bank_account_no" name="bank_account_no" placeholder="Add meg a bankszámlaszámod" value="{{ old('bank_account_no') }}" required>
		</div>

		<div class="form-group">
			<label for="password">Jelszó</label>
			<input type="password" id="password" name="password" placeholder="Add meg a jelszavad" required>
		</div>

		<div class="form-group">
			<label for="password_confirmation">Jelszó megerősítése</label>
			<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Erősítsd meg a jelszavad" required>
		</div>

		<button type="submit" class="mb-4">Regisztráció</button>
        <div class="w-100 d-flex justify-content-end">
            <a href="{{ route('login') }}" >Már van fiókod? Jelentkezz be >>></a>
        </div>
	</form>
@endsection
