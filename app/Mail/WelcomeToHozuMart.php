<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\UserEmailConfirmation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeToHozuMart extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'my-theme';

    public $user;
    public $conf;
    public $url;

    public function __construct(User $user, UserEmailConfirmation $conf, $url)
    {
        $this->user = $user;
        $this->conf = $conf;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome_to_hozumart');
    }
}
