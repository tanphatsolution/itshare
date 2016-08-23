<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\ContestCategory;
use Carbon\Carbon;

class ContestCategoryTableSeeder extends Seeder
{

    public function run()
    {
        ContestCategory::create([
            'contest_id' => 1,
            'category_id' => 1,
        ]);

        ContestCategory::create([
            'contest_id' => 1,
            'category_id' => 2,
        ]);
    }
}
