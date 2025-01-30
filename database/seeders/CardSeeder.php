<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cards')->insert([
            [
                'user_id' => 1,
                'account_id' => 1,
                'card_number' => '3778********1234',
                'holder_name' => 'Eddy Cusuma',
                'balance' => 5756.00,
                'type' => 'Secondary',
                'bank' => 'DBL Bank',
                'expired_date' => '2022-12-31',
                'status' => 'active'
            ]
        ]);

        DB::table('cards')->insert([
            [
                'user_id' => 1,
                'account_id' => 1,
                'card_number' => '************5600',
                'holder_name' => 'William',
                'balance' => 0.00,
                'type' => 'Secondary',
                'bank' => 'BRC Bank',
                'expired_date' => '2022-12-31',
                'status' => 'active'
            ]
        ]);

        DB::table('cards')->insert([
            [
                'user_id' => 1,
                'account_id' => 1,
                'card_number' => '************4300',
                'holder_name' => 'Michel',
                'balance' => 0.00,
                'type' => 'Secondary',
                'bank' => 'ABM Bank',
                'expired_date' => '2022-12-31',
                'status' => 'active'
            ]
        ]);

        $this->command->info('Cards created.');
    }
}
