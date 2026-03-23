<?php

namespace App\Mail;

use App\Models\Answer;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAnswerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public Answer $answer,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Válasz érkezett a kapcsolatfelvételi üzenetedre',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.answer_email',
            with: [
                'user' => $this->contactMessage->user,
                'messageText' => $this->contactMessage->message,
                'answerText' => $this->answer->message,
            ],
        );
    }
}
