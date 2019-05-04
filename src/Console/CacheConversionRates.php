<?php
/**
 * User: msonowal
 * Date: 21/03/17
 * Time: 2:56 PM
 */
namespace Msonowal\Laraxchange\Console;

use Illuminate\Console\Command;
use Msonowal\Laraxchange\Facades\Currency;
use Illuminate\Support\Facades\Log;

class CacheConversionRates extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'currency:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache currency rates from the LIVE API Provider to the default cache store as specified in the config';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $msg = "Starting update for Currency API to cache...\n";
        $this->output->write($msg);
        Log::info($msg);
        foreach (getAvailableCurrencies() as $currency_code) {
            $msg = 'Updating for '.$currency_code;
            $this->info($msg);
            Log::info($msg);
            Currency::setBaseCurrency($currency_code);
            Currency::updateRates();
        }
        $msg = "<info>Update complete</info>";
        $this->output->writeln($msg);
        Log::info($msg);
    }
}
