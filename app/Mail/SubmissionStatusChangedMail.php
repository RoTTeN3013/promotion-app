<?php

namespace App\Mail;

use App\Helpers\SubmissionStatusHelper;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Submission $submission)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Feltöltés státusz frissítés #' . $this->submission->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.submission_status_changed',
            with: [
                'submission' => $this->submission,
                'statusLabel' => SubmissionStatusHelper::label($this->submission->status),
            ],
        );
    }
}
