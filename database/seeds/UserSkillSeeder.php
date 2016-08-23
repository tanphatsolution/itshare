<?php
use App\Data\Blog\Skill;
use App\Data\Blog\UserSkill;

class UserSkillSeeder extends Seeder
{

    public function run()
    {
        $skillIds = Skill::lists('id');
        UserSkill::WhereNotIn('skill_id', $skillIds)->delete();
    }

}