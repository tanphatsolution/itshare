<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\AnswerHelpful;

class AnswerHelpfulTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run()
    {
        AnswerHelpful::truncate();

        $data = [
        	'answer_id' => 1,
        	'user_id' => 1,
        ];

        AnswerHelpful::create($data);
    }
}
