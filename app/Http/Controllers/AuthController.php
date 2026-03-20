<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Models
use App\Models\User;


class AuthController extends Controller
{

    public function Register() {
        return view('auth.register_form');
    }

    public function Login() {
        return view('auth.login_form');
    }

    public function RegisterUser(Request $request) {
        //A form adatok validálása
        $validated = $request->validate([
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'phone_no' => [
                'required',
                'regex:/^\+?[0-9\s\-()]{8,20}$/'
            ],
            'email' => 'required|email|unique:users,email',
            'bank_account_no' => [
                'required',
                'regex:/^\d{8}-\d{8}(-\d{8})?$/',
                'unique:users,bank_account_no'
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'required' => 'A(z) :attribute mező kitöltése kötelező.',
            'string' => 'A(z) :attribute mező csak szöveget tartalmazhat.',
            'email' => 'A(z) :attribute mezőnek érvényes e-mail címnek kell lennie.',
            'min.string' => 'A(z) :attribute mező legalább :min karakter hosszú legyen.',
            'max.string' => 'A(z) :attribute mező legfeljebb :max karakter hosszú lehet.',
            'unique' => 'A(z) :attribute már használatban van.',
            'confirmed' => 'A jelszó megerősítése sikertelen.',
            'phone_no.regex' => 'A telefonszám formátuma nem megfelelő.',
            'bank_account_no.regex' => 'A bankszámlaszám formátuma nem megfelelő. Példa: 12345678-12345678 vagy 12345678-12345678-12345678.',
        ], [
            'first_name' => 'keresztnév',
            'last_name' => 'vezetéknév',
            'phone_no' => 'telefonszám',
            'email' => 'e-mail cím',
            'bank_account_no' => 'bankszámlaszám',
            'password' => 'jelszó',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->to('login')->with('success', 'Sikeres regisztráció! Most már bejelentkezhetsz!');
    }

    public function LogUserIn(Request $request) {
        //A form adatok validálása
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!Auth::attempt($validated)) {
            return redirect()->back()->with(['error' => 'Hibás adatokat adtál meg!']);
        }

        $user = Auth::user();

        return view('dashboard')->with('success', 'Sikeres bejelentkezés! Üdv újra itt ' . $user->first_name . '!');
    }

    public function logUserOut() {
        Auth::logout();
        return redirect()->to('login')->with('success', 'Sikeres kijelentkezés!');
    }
}
