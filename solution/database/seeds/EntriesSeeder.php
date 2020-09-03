<?php

use App\User;
use Illuminate\Database\Seeder;

class EntriesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entry::class, 99)->create(['user_id' => 1]);
        foreach (User::all() as $user) {
            $user->refreshBalance();
        }
    }
}
