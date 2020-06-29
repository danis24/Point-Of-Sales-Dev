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
                'bank_name' => 'BCA',
                'account_number' => '186718676712',
                'account_name' => 'Danis Yogaswara'
            ]
        ));
    }
}
