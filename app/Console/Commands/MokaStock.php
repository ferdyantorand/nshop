<?php

namespace App\Console\Commands;

use App\libs\Moka;
use App\Models\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MokaStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moka_stock:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            //moka stock synchronize
            //checking moka stock, and update stock
            $mokaToken = Configuration::where("configuration_key", "moka_token")->first();
            $mokaStock = Moka::getItems($mokaToken->configuration_value);
            $productSync = Moka::ItemSynchronize($mokaStock);
            if($productSync == "success"){
                Log::channel('cronjob')->info("success synchronize");
            }
            else{
                Log::channel('cronjob')->error("error while synchronize in Moka.php, error = ".$productSync);
            }
        }
        catch (\Exception $ex){
            Log::channel('cronjob')->error($ex);
        }
    }
}
