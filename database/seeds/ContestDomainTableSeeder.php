<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\ContestDomain;

class ContestDomainTableSeeder extends Seeder
{

    public function run()
    {
        ContestDomain::create([
            'contest_id' => 1,
            'domain_id' => 1,
        ]);

        ContestDomain::create([
            'contest_id' => 1,
            'domain_id' => 2,
        ]);
    }
}
