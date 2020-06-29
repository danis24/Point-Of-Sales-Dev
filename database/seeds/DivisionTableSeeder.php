<?php

use Illuminate\Database\Seeder;

class DivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert(array(
        	[
	            'name'			=>	'ERSO PRIDATAMA',
            ],
            [
	            'name'			=>	'GOMBRANG',
            ],
            [
	            'name'			=>	'UPWIDE',
            ],
            [
	            'name'			=>	'MARKAS SUBLIM',
            ],
        ));
    }
}
