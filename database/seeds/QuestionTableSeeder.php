<?php
use Illuminate\Database\Seeder;
use App\Data\Faq\Question;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run()
    {
        Question::truncate();

        $data = [
        	'title' => 'Apache Alias not working',
        	'slug' => 'apache-alias-not-working',
        	'content' => 'down vote favorite I hope that apache operates as below. request : http://SERVER_IP/tempfile/image/aaa.jpg actual file path : /data/temp/file/image/aaa.jpg so I set tempfile.conf. Alias /tempfile /data/temp/file <Directory /data/temp/file>',
        	'language_code' => 'en',
        	'number_clip' => 10,
        	'number_view' => 10,
        	'number_answer' => 10,
        	'blocked' => 1,
        	'first_time_answer' => 0,
        	'user_id' => 1
        ];

        Question::create($data);
    }
}
