<?php

use Illuminate\Database\Seeder;
use App\User;

class NewAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
          $user->password = bcrypt($user->password);
          $user->save();
        }
    }
}
