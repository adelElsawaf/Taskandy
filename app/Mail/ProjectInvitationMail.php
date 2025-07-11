<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $inviterName,
        public string $projectName,
        public string $inviteLink,
    ) {}

    public function build()
    {
        return $this->subject("You're invited to join {$this->projectName}")
                    ->view('emails.project-invitation')
                    ->with([
                        'inviterName' => $this->inviterName,
                        'projectName' => $this->projectName,
                        'inviteLink' => $this->inviteLink,
                    ]);
    }
}