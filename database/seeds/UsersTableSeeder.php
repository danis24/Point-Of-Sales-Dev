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
        		'name'		=>	'Danis Yogaswara',
        		'email'		=>	'danistutorial@gmail.com',
        		'password'	=>	bcrypt('Suckhack24@'),
        		'photos'	=>	'lendis.jpg',
        		'level'		=>	1
        	],
        	[
        		'name'		=>	'Nenden Dea Febriyanti',
        		'email'		=>	'dea@erso-pridatama.com',
        		'password'	=>	bcrypt('zabuza71'),
        		'photos'	=>	'avatar.png',
        		'level'		=>	2
        	]
        ));
    }
}
