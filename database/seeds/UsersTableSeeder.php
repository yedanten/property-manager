<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::find(1);

        $user = new User;
        $user->name = 'admin';
        $user->password = bcrypt('123456');
        $user->email = 'admin@admin.cc';

        $role->user()->save($user);
    }
}
