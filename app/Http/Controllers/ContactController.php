<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

use App\Mail\ContactAnswerMail;
use App\Models\Answer;
use App\Models\ContactMessage;

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

        ContactMessage::create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
            'status' => 'received',
        ]);

        return back()->with('success', 'Üzenetedet sikeresen elküldtük. Hamarosan jelentkezünk.');
    }
}
