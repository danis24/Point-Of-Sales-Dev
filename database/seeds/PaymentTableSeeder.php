<?php

use Illuminate\Database\Seeder;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->insert(array(
        	[
                'type'			=>	'cash',
                'bank_name' => '',
                'account_number' => '',
                'account_name' => ''
            ],
            [
                'type'			=>	'bank',
                'bank_name' => 'BCA ERSO',
                'account_number' => '2831920086',
                'account_name' => 'Danis Yogaswara'
            ],
            [
                'type'			=>	'bank',
                'bank_name' => 'BCA MARKAS',
                'account_number' => '2831612667',
                'account_name' => 'Erwin Fahmi Sobirin'
            ]
        ));
    }
}
