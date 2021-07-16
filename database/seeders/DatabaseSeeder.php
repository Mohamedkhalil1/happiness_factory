<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        User::factory(10)->create();
        User::create([
            'name'     => 'mohamed khalil',
            'email'    => 'mohamed@template.test',
            'password' => bcrypt('123456'),
        ]);
        $this->call(TransactionSeeder::class);
    }
}
