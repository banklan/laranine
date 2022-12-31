<?php

namespace App\Jobs;

use App\Admin;
use App\Service;
use Illuminate\Bus\Queueable;
use App\Mail\NewServiceCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewServiceCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $new_service = new NewServiceCreated($this->service);

        // send to admins
        $admins = Admin::where('status', 1)->get();
        foreach($admins as $admin){
            Mail::to($admin->email)->send($new_service);
        }
    }
}
