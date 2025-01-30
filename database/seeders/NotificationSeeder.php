<?php

namespace Database\Seeders;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@test.com')->first();
        for ($n = 0; $n < 5; $n++) {
            Notification::make()
                ->title('New order')
                ->icon('heroicon-o-shopping-bag')
                ->body('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel enim ut odio volutpat sollicitudin eget eu augue. Mauris sed lacus eget urna porta aliquam. Etiam quis semper tellus. Nunc dolor risus, hendrerit eu pretium et, consectetur ut odio. Aliquam egestas erat non sollicitudin posuere. Fusce maximus hendrerit augue eget pretium.')
                ->sendToDatabase($user);
        }
        $this->command->info('Notifications created.');
    }
}
