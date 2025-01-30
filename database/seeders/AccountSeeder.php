<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts')->insert([
            [
                'user_id' => 1,
                'account_number' => "123456789",
                'balance' => 0.00,
                'type' => 'Secondary',
                'status' => 'active'
            ]
        ]);
        $this->command->info('Accounts created.');
    }
}
