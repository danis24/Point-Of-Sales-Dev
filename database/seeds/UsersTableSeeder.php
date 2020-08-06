<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
        	[
        		'name'		=>	'Nenden Dhea Febriyanti',
        		'email'		=>	'dea@erso-pridatama.com',
        		'password'	=>	bcrypt('zabuza71'),
        		'photos'	=>	'lendis.jpg',
        		'level'		=>	1
			],
			[
        		'name'		=>	'Danis Yogaswara',
        		'email'		=>	'danis@erso-pridatama.com',
        		'password'	=>	bcrypt('Suckhack24@'),
        		'photos'	=>	'lendis.jpg',
        		'level'		=>	0
        	],
        	[
        		'name'		=>	'Atiya',
        		'email'		=>	'atiya@erso-pridatama.com',
        		'password'	=>	bcrypt('zabuza71'),
        		'photos'	=>	'avatar.png',
        		'level'		=>	2
			],
			[
        		'name'		=>	'LEON',
        		'email'		=>	'leon@erso-pridatama.com',
        		'password'	=>	bcrypt('zabuza71'),
        		'photos'	=>	'avatar.png',
        		'level'		=>	3
        	]
        ));
    }
}
