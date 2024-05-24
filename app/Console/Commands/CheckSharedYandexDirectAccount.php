<?php

namespace App\Console\Commands;

use App\Facades\YandexDirect;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckSharedYandexDirectAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-shared-yandex-direct-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if the shared account is connected';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $clients = Client::where('is_enable_shared_account', false)->whereNot('account_id', null)->get()->pluck('login');

        if($clients->isEmpty()) return;

        $response = YandexDirect::getDisabledSharedAccount($clients->toArray());

        if(isset($response->result->Clients) && !empty($response->result->Clients))
        {
            $clients = $response->result->Clients;

            foreach($clients as $client)
            {
                $key = array_search('SHARED_ACCOUNT_ENABLED', array_column($client->Settings, 'Option'));

                if($client->Settings[$key]->Value == "YES")
                {
                    try {
                        DB::beginTransaction();

                        DB::table(Client::getTableName())
                            ->where('client_id', $client->ClientId)
                            ->update(['is_enable_shared_account' => true]);

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error($e->getMessage());
                    }
                }
            }
        }
    }
}
