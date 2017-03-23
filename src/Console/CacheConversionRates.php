<?php
/**
 * User: msonowal
 * Date: 21/03/17
 * Time: 2:56 PM
 */
namespace Msonowal\Laraxchange\Console;

use Illuminate\Console\Command;
use Msonowal\Laraxchange\Facades\Currency;

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
    public function fire()
    {
        $this->output->write("Starting update for Currency API to cache...\n");
        foreach (getAvailableCurrencies() as $currency_code) {
            $this->info('Updating for '.$currency_code);
            Currency::setBaseCurrency($currency_code);
            Currency::updateRates();
        }
        $this->output->writeln("<info>Update complete</info>");
    }
}
