<?php

namespace App\Jobs;

use App\Facades\YandexDirect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportYandexDirect implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $report;

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
        YandexDirect::generateReposts($this->user, $this->report);
    }
}
