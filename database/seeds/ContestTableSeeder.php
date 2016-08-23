<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\Contest;
use Carbon\Carbon;

class ContestTableSeeder extends Seeder
{

    public function run()
    {
        Contest::create([
            'title' => 'Framgia Contest',
            'monthly_theme_subject_id' => 1,
            'user_id' => '1',
            'term_start' => Carbon::now(),
            'term_end' => Carbon::now()->addMonth(),
        ]);
    }
}
