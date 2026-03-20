@extends('layouts.auth')

@section('title', 'Bejelentkezés')
@section('heading', 'Bejelentkezés')

@section('content')
	<form method="POST" action="{{ route('login.user') }}">
		@csrf

		<div class="form-group">
			<label for="email">Email cím</label>
			<input type="email" id="email" name="email" placeholder="Add meg az email címed" required value="{{ old('email') }}">
		</div>

		<div class="form-group">
			<label for="password">Jelszó</label>
			<input type="password" id="password" name="password" placeholder="Add meg a jelszavad" required>
		</div>

		<button class="mb-4" type="submit">Bejelentkezés</button>
		<div class="w-100 d-flex justify-content-end">
			<a href="{{ route('register') }}" >Nincs még fiókod? Regisztrálj most >>></a>
		</div>
	</form>
@endsection
