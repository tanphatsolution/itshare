<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\Answer;

class AnswerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run()
    {
        Answer::truncate();

        $data = [
        	'content' => 'done',
        	'number_helpful' => 10,
        	'user_id' => 1,
        	'question_id' => 1
        ];

        Answer::create($data);
    }
}
