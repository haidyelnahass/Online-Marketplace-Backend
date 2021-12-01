<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(15)->create()->each(function ($user) {

            $store = Store::create(['name' => $user->name . '\'s Store.', 'user_id' => $user->id]);
        });
    }
}
