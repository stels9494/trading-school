<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        	[
        		'name' => 'date_trading_start',
        		'description' => 'Дата начала торгов',
        		'type' => 'datetime',
        		'value' =>  new Carbon('2020-01-01'),
        	],[
        		'name' => 'date_trading_finish',
        		'description' => 'Дата окончания торгов',
        		'type' => 'datetime',
        		'value' => new Carbon('2020-10-10'),
        	],[
        		'name' => 'status',
        		'description' => 'текущее состояние торгов',
        		'type' => 'bool',
        		'value' => false,
        	],[
        		'name' => 'month_in_minut',
        		'description' => 'сколько минут длится месяц',
        		'type' => 'integer',
        		'value' => 2,
        	],[
                'name' => 'current_date',
                'description' => 'какой время внутри торгов',
                'type' => 'datetime',
                'value' => new Carbon('2020-01-01'),
            ]
        ];

        foreach ($data as $datum)
        {
        	Setting::create($datum);
        }
    }
}
