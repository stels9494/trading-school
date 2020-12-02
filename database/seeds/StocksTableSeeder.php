<?php

use Illuminate\Database\Seeder;
use App\Models\Stock;

class StocksTableSeeder extends Seeder
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
        		'name' => 'APPLE',
        	],[
        		'name' => 'BABA',
        	],[
        		'name' => 'AMAZON',
        	],[
        		'name' => 'TESLA',
        	],
        ];

        foreach ($data as $datum)
        {
        	Stock::create($datum);
        }
    }
}
