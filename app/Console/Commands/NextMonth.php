<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Setting;

class NextMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'next-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Перевести игру на след месяц';

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
     * @return int
     */
    public function handle()
    {
        if (Setting::getValueByName('status'))
        {
            // првоерить, что не последний месяц
            $currentDate = Setting::getValueByName('current_date');
            if ($currentDate < Setting::getValueByName('date_trading_finish'))
            {
                // перевести
                Setting::setValueByName('current_date', $currentDate->addMonth());

                foreach (\App\Models\Command::query()->get() as $command){
                    broadcast(new \App\Events\UpdateCharts($command, [$currentDate]));
                }

            } else {
                // иначе остановить игру
                Setting::setValueByName('status', false);
                //Setting::setValueByName('current_date', Setting::getValueByName('date_trading_start'));
            }
        }

        return 0;
    }
}
