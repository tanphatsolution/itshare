<?php
use Illuminate\Database\Seeder;
use App\Data\System\User;

class UserTableSeeder extends Seeder
{

    public function run()
    {
    	User::truncate();

        $data = [
            'name' => 'Nghe roi',
            'username' => 'ngheroi',
            'password' => '123456789',
            'email' => 'ngheroi@framgia.com'
        ];

        User::create($data);
    }

}