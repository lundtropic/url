<?php

use Illuminate\Database\Seeder;

use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Super User';
        $user->email = 'super@tropicsurvival.com';
        $user->password = bcrypt('SuperTropic5000');
        $user->save();
    }
}
