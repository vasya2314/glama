<?php

namespace App\Events;

use App\Models\Report;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportHasBeenGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $report;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }
}
