<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_list = ['管理员', '操作员'];
        foreach ($role_list as $key => $value) {
            $role = new Role;
            $role->name = $value;
            $role->save();
        }
    }
}
