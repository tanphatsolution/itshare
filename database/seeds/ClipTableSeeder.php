<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\Clip;

class ClipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run()
    {
        Clip::truncate();

        $data = [
        	'user_id' => 1,
        	'question_id' => 1,
        ];

        Clip::create($data);
    }
}
