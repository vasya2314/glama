<?php

namespace App\Jobs;

use App\Facades\YandexDirect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class GenerateReportYandexDirect implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $report;

    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $report)
    {
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(YandexDirect::updateReport($this->user, $this->report) == false) {
            $this->release(60);
        }
    }
}
