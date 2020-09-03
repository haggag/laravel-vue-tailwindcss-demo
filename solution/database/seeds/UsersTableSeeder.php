<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::first()) {
            factory(User::class)->create([
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => Hash::make('P@ssw0rd'),
            ]);
        }
    }
}
