<?php

namespace App\Jobs;

use App\User;
use App\Service;
use Illuminate\Bus\Queueable;
use App\Mail\ServiceHasBeenCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MyServiceCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $service;
    public $user;

    public function __construct(Service $service, User $user)
    {
        $this->service = $service;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $new_service = new ServiceHasBeenCreated($this->service, $this->user);

        // send mail to user
        $user = auth('api')->user();
        Mail::to($user->email)->send($new_service);
    }
}
