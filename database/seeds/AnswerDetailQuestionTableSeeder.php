<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\AnswerDetailQuestion;

class AnswerDetailQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run()
    {
        AnswerDetailQuestion::truncate();

        $data = [
        	'content' => '',
        	'user_id' => 1,
        	'request_detail_question_id' => 1
        ];

        AnswerDetailQuestion::create($data);
    }
}
