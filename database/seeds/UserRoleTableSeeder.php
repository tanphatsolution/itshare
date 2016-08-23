<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\UserRole;
use App\Data\Blog\Role;

class UserRoleTableSeeder extends Seeder
{

    public function run()
    {
        UserRole::createOrUpdate(1, Role::ADMIN);
    }

}