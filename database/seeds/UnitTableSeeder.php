<?php

use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert(array(
        	[
	            'name'			=>	'botol',
            ],
            [
	            'name'			=>	'buah',
            ],
            [
	            'name'			=>	'bungkus',
            ],
            [
	            'name'			=>	'kilogran',
            ],
            [
	            'name'			=>	'gram',
            ],
            [
	            'name'			=>	'liter',
            ],
            [
	            'name'			=>	'pack',
            ],
            [
	            'name'			=>	'box',
            ],
            [
	            'name'			=>	'dus',
            ],
        ));
    }
}
