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
        $user->email = config('seed.user.email');
        $user->password = bcrypt(config('seed.user.password'));
        $user->save();
    }
}
