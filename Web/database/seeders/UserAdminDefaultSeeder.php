<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAdminDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::withoutEvents(function () {
            User::create([
                'id'                => (string)Str::orderedUuid(),
                'name'              => 'Admin Plat System',
                'email'             => 'admin@plats.network',
                'password'          => Hash::make('123456a@'),
                'role'              => ADMIN_ROLE,
                'email_verified_at' => now(),
            ]);

            //Add user client

        });
    }
}
