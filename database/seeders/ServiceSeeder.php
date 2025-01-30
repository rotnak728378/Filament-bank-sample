<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Money Transfer',
                'description' => 'Send money anywhere in the world quickly and securely',
                'fee' => 25.00,
                'status' => 'active',
            ],
            [
                'name' => 'Bill Payment',
                'description' => 'Pay your utilities and other bills conveniently',
                'fee' => 10.00,
                'status' => 'active',
            ],
            [
                'name' => 'Check Processing',
                'description' => 'Quick and efficient check processing services',
                'fee' => 15.00,
                'status' => 'active',
            ],
            [
                'name' => 'Safe Deposit Box',
                'description' => 'Secure storage for your valuables',
                'fee' => 50.00,
                'status' => 'active',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
