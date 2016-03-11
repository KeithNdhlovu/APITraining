<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Cache;
use Slack;

class UpdateExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the latest USD/ZAR exchange rate and stores it in local cache.';

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
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://api.fixer.io/latest?base=USD&symbols=ZAR');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (!$response) {
            echo 'Could not get response from fixer.io API!';
            return;
        }

        $responseObject = json_decode($response);

        if (!isset($responseObject->base) || !isset($responseObject->rates) || !isset($responseObject->rates->ZAR)) {
            echo 'Did not get expected json from fixter.io API!';
            return;
        }

        $cacheKey = 'usd_to_zar';

        if (!Cache::has($cacheKey)) {
            Cache::forever($cacheKey, $responseObject->rates->ZAR);
            return;
        }

        $oldRate = Cache::get($cacheKey);
        $newRate = $responseObject->rates->ZAR;

        Cache::forever($cacheKey, $newRate);

        $percentage = round((($newRate * 100) / $oldRate) - 100, 2);  

        Slack::send($newRate . ' (' . $percentage . '%)');
    }
}
