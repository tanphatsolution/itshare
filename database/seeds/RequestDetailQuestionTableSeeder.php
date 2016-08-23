<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\RequestDetailQuestion;

class RequestDetailQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run()
    {
        RequestDetailQuestion::truncate();

        $data = [
        	'content' => '',
        	'user_id' => 1,
        	'question_id' => 1
        ];

        RequestDetailQuestion::create($data);
    }
}
