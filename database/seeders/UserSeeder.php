<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create storage directory if it doesn't exist
        Storage::disk('public')->makeDirectory('users');

        // Source image path (put your image in storage/app/public/seed/default.jpg)
        $sourcePath = storage_path('app/public/images/eddy.png');

        // Destination path
        $imageName = time().'.png';
        $destinationPath = 'users/' . $imageName;

        // Copy image if source exists
        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));
        }

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'image' => $destinationPath // Store relative path
        ]);

        // Create roles
        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        // Assign role to admin
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $adminRole->id
        ]);

        $this->command->info('Users created with images.');
    }
}
