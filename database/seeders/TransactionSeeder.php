<?php
namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::insert([
            [
                'user_id' => 1,
                'account_id' => 1,
                'card_id' => 1,
                'description' => 'Spotify Subscription',
                'type' => 'Shopping',
                'amount' => -850.00,
                'status' => 'completed',
                'created_at' => now()->addDays(-3)->toDateTimeString(),
            ],
            [
                'user_id' => 1,
                'account_id' => 1,
                'card_id' => 1,
                'description' => 'Freepik Sales',
                'type' => 'Transfer',
                'amount' => 750.00,
                'status' => 'completed',
                'created_at' => now()->toDateTimeString(),
            ],
            [
                'user_id' => 1,
                'account_id' => 1,
                'card_id' => 2,
                'description' => 'Deposit PayPal',
                'type' => 'Deposit',
                'amount' => 2500.00,
                'status' => 'completed',
                'created_at' => now()->addDays(-3)->toDateTimeString(),
            ]
        ]);

        $this->command->info('Transaction created.');
    }
}
