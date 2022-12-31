<?php

namespace App\Mail;

use App\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewServiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'my-theme';

    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new_service_created');
    }
}
