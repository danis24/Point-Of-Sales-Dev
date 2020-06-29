<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert(array(
        	[
	            'category_name'			=>	'JERSEY',
            ],
            [
	            'category_name'			=>	'KESEHATAN',
            ],
            [
	            'category_name'			=>	'BAJU ANAK',
            ],
        ));
    }
}
