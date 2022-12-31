<?php

namespace App\Mail;

use App\User;
use App\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceHasBeenCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'my-theme';

    public $service;
    public $user;

    public function __construct(Service $service, User $user)
    {
        $this->service = $service;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.service_has_been_created');
    }
}
