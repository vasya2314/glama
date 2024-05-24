<?php

namespace App\Console\Commands;

use App\Facades\YandexDirect;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckClientCampaignsQty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-client-campaigns-qty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the number of user companies';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $logins = Client::all()->pluck('login');

        if($logins->isNotEmpty())
        {
            $logins->each(function ($login) {
                try {
                    DB::beginTransaction();

                    $response = YandexDirect::getClientCampaignsQty($login);

                    if(is_numeric($response))
                    {
                        DB::table(Client::getTableName())
                            ->where('login', $login)
                            ->update(['qty_campaigns' => $response]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage());
                }
            });
        }
    }
}
