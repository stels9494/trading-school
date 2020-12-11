<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
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
    			'name' => 'admin',
    			'label' => 'Админ',
    		],
    		[
    			'name' => 'commander',
    			'label' => 'Капитан',
    		],
    		[
    			'name' => 'member',
    			'label' => 'Участник',
    		],
    	];

    	foreach ($data as $datum)
    	{
	        config('permission.models.role')::create($datum);
    	}
    }
}
