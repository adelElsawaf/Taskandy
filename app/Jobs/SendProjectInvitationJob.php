<?php

namespace App\Jobs;

use App\DTOs\ProjectInvitationDTO;
use App\Mail\ProjectInvitationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendProjectInvitationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ProjectInvitationDTO $dto) {}

    public function handle()
    {
        Log::info("📤 Sending invite to {$this->dto->email} via Gmail");

        Mail::to($this->dto->email)->send(
            new ProjectInvitationMail(
                $this->dto->inviterName,
                $this->dto->projectName,
                $this->dto->inviteLink
            )
        );
    }

    public $tries = 3;
    public $backoff = 5;
}