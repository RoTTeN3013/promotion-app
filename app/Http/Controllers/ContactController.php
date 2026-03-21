<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(Request $request): View
    {
        return view('contact', [
            'user' => $request->user(),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'message.required' => 'Az üzenet mező kitöltése kötelező.',
            'message.min' => 'Az üzenet legalább :min karakter legyen.',
            'message.max' => 'Az üzenet legfeljebb :max karakter lehet.',
        ]);

        $user = $request->user();
        $recipient = config('services.support.email', config('mail.from.address'));

        Mail::send('emails.contact_message', [
            'user' => $user,
            'messageText' => $validated['message'],
        ], function ($mail) use ($user, $recipient): void {
            $mail->to($recipient)
                ->replyTo($user->email, trim($user->first_name . ' ' . $user->last_name))
                ->subject('Új kapcsolatfelvételi üzenet: ' . trim($user->first_name . ' ' . $user->last_name));
        });

        return back()->with('success', 'Üzenetedet sikeresen elküldtük. Hamarosan jelentkezünk.');
    }
}
