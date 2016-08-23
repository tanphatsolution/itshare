<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\Domain;

class DomainTableSeeder extends Seeder
{

    public function run()
    {
        Domain::create(['name' => '@framgia.co.jp']);
        Domain::create(['name' => '@framgia.com']);
    }
}
