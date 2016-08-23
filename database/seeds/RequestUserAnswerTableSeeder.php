<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\RequestUserAnswer;

class RequestUserAnswerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        RequestUserAnswer::truncate();

        $data = [
        	'user_id' => 1,
        	'question_id' => 1,
        ];

        RequestUserAnswer::create($data);
    }
}
