<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\SkillCategory;
use App\Data\Blog\Skill;

class SkillCategorySeeder extends Seeder
{

    public function run()
    {
        DB::table('skills')->truncate();
        DB::table('skill_categories')->truncate();
        if (($handle = fopen(__DIR__ . '/skills.csv', 'r')) !== false) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $row++;
                if ($row === 1) {
                    continue;
                } else {
                    $skillCategory = SkillCategory::firstOrNew(['id' => $data[0]]);
                    $skillCategory->name = $data[1];
                    $skillCategory->short_name = $data[2];
                    $skillCategory->default_flag = 1;
                    $skillCategory->save();
                    Skill::create([
                        'skill_category_id' => $data[0],
                        'default_flag' => 1,
                        'name' => $data[3],
                        'short_name' => $data[4]
                    ]);
                }
            }
            fclose($handle);
        }
    }

}