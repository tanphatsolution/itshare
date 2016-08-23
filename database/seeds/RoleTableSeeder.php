<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\Role;

class RoleTableSeeder extends Seeder
{

    public function run()
    {
        $titles = [
            'member',
            'moderator',
            'admin',
        ];
        foreach ($titles as $title) {
            Role::create([
                'title' => $title,
            ]);
        }
    }

}