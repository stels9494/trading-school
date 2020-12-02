<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/* Добавление админа */
        $data = [
        	[
        		'user' => [
	    			'firstname' => 'администратор',
	    			'login' => 'admin',
	    			'password' => '123456',
	    			'email' => 'admin@mail.ru',
        		],
        		'roles' => ['admin'],
        	],
        ];

        foreach ($data as $datum)
        {
        	User::create($datum['user'])->assignRole($datum['roles']);
        }
    }
}
