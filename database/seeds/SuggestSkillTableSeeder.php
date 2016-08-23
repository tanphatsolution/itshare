<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\SuggestSkill;

class SuggestSkillTableSeeder extends Seeder
{

    public function run()
    {
        if (($handle = fopen(__DIR__ . '/suggestSkills.csv', 'r')) !== false) {
            $row = 0;
            while (($data = fgetcsv($handle, 2000, ';')) !== false) {
                $row++;
                if ($row === 1) {
                    continue;
                } else {
                    SuggestSkill::create([
                        'short_name' => $data[0],
                        'name' => $data[1],
                    ]);
                }
            }
            fclose($handle);
        }
    }

}