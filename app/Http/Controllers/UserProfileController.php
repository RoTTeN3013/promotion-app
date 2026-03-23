<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('user_profile_edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_no' => ['required', 'regex:/^\+?[0-9\s\-()]{8,20}$/', Rule::unique('users', 'phone_no')->ignore($user->id)],
            'bank_account_no' => ['required', 'regex:/^\d{8}-\d{8}(-\d{8})?$/', Rule::unique('users', 'bank_account_no')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_no' => $validated['phone_no'],
            'bank_account_no' => $validated['bank_account_no'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        if ($user->email !== $validated['email']) {
            $updateData['email_verified_at'] = null;
        }

        $user->update($updateData);

        return back()->with('success', 'Profil adatai sikeresen frissítve.');
    }
}
