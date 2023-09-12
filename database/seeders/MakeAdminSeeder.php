<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Seeder;

class MakeAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = UserModel::create([
            'name' => 'Main Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $user->assignRole("super-admin");
    }
}
