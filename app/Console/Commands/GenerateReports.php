<?php

namespace App\Console\Commands;

use App\Facades\YandexDirect;
use App\Models\Client;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate reports for clients';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::with('clients')->where('created_at', '<', now()->startOfMonth())->chunk(150, function (Collection $users)
        {
            if($users->isNotempty())
            {
                $users->each(function (User $user)
                {
                    YandexDirect::generateReport($user);
                });
            }
        });
    }
}
