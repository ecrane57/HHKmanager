<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class twoFactor extends Mailable
{
    use Queueable, SerializesModels;


	/**
     * The user instance.
     *
     * @var User
     */
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@nonprofitsoftwarecorp.org', 'NPSeCure Dashboard')
        		->subject("Two Factor Verification Code")
                ->text('emails.2fatoken');
    }
}
