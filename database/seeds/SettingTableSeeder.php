<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert(array(
        	[
	            'company_name'			=>	'ERSO PRIDATAMA',
	            'company_address'		=>	'Kp. Cibiru Tonggoh RT 02 RW 07 Ds. Cibiru Wetan Kec. Cilenyi',
	            'company_phone_number'	=>	'089669123646',
	            'company_logo'			=>	'shield.svg',
	            'member_card'			=>	'card.jpg',
	            'member_discount'		=>	'0',
	            'note_type'				=>	'0'
	        ]

        ));
    }
}
