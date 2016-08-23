<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\QuestionCategory;

class QuestionCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionCategory::truncate();

        $data = [
			'question_id' => 1,
			'category_id' => 1	
        ];

        QuestionCategory::create($data);
    }
}
