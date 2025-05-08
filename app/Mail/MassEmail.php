<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MassEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $emailSubject;
    public $emailContent;

    public function __construct(User $user, string $subject, string $content)
    {
        $this->user = $user;
        $this->emailSubject = $subject;
        $this->emailContent = $content;
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
            ->view('emails.mass-email');
    }
}
