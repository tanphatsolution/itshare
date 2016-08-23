<?php namespace App\Services;

use DB;
use Exception;
use App\Data\Blog\SkillCategory;
use App\Data\Blog\Skill;
use App\Data\Blog\UserSkill;

class UserSkillService
{
    public static function saveSkillSet($skillSets, $userId)
    {
        foreach($skillSets as $skillSet) {
            if (empty($skillSet['newCategory']) || !isset($skillSet['newSkill'])) {
                continue;
            }
            $name = strtolower($skillSet['newCategory']);
            $dbCountSkill = SkillCategory::where(DB::Raw('LOWER (`name`)'), 'like', $name)->count();
            if ($dbCountSkill > 0) {
                throw new  Exception($name . ' '.trans('messages.user_skill.category_skill_exist'), 1);
            }
            $skillCategory = new SkillCategory;
            $skillCategory->name = $name;
            $skillCategory->short_name = $skillSet['newCategory'];
            $skillCategory->user_id = $userId;
            $skillCategory->save();
            $skillSetCount = count($skillSet['newSkill']['skillName']);
            for($i = 0; $i < $skillSetCount; $i++) {
                if (empty($skillSet['newSkill']['skillName'][$i]) || empty($skillSet['newSkill']['skillYear'][$i])) {
                    continue;
                }
                $name = $skillSet['newSkill']['skillName'][$i];
                $dbCountSkill = Skill::where(DB::Raw('LOWER (`name`)'), 'like', $name)->count();
                if ($dbCountSkill > 0) {
                    throw new  Exception($name . ' '.trans('messages.skill.skill_exist'), 1);
                }
                $skill = new Skill;
                $skill->name = $name;
                $skill->short_name = $skillSet['newSkill']['skillName'][$i];
                $skill->skill_category_id = $skillCategory->id;
                $skill->user_id = $userId;
                $skill->save();

                $userSkill = new UserSkill;
                $userSkill->user_id = $userId;
                $userSkill->skill_id = $skill->id;
                $userSkill->year = $skillSet['newSkill']['skillYear'][$i];
                $userSkill->save();
            }
        }
    }

    public static function saveSubSkill($skill, $userId)
    {
        $userSkill = UserSkill::firstOrNew(['user_id' => $userId, 'skill_id' => $skill[0]]);
        if ($skill[1] == UserSkill::SKILL_DELETE_FLAG) {
            $userSkill->delete();
        } else {
            $userSkill->year = (float) $skill[1];
            $userSkill->save();
        }
    }

    public static function skillEditable($skill, $user)
    {
        if ($skill->default_flag || $skill->user_id !== $user->id) {
            return false;
        }
        return true;
    }

    public static function skillDisplayable($skill, $user)
    {
        if ($skill->default_flag || $skill->user_id == $user->id) {
            return true;
        }
        return false;
    }

    public static function skillCategoryDisplayable($skillCategory, $user)
    {
        if ($skillCategory->default_flag || $skillCategory->user_id == $user->id) {
            return true;
        }
        return false;
    }

    public static function skillCategoryEditable($skillCategory, $user)
    {
        if ($skillCategory->default_flag || $skillCategory->user_id !== $user->id) {
            return false;
        }
        return true;
    }

    public static function saveCategorySkills($categorySkills, $userId)
    {
        foreach ($categorySkills as $categoryId => $skills) {
            if (!isset($skills['skillName'])) {
                continue;
            }

            $skillNameCount = count($skills['skillName']);
            for($i = 0; $i < $skillNameCount; $i++) {
                if (empty($skills['skillName'][$i])) {
                    continue;
                }
                $name = strtolower($skills['skillName'][$i]);
                $dbCountSkill = Skill::where(DB::Raw('LOWER (`name`)'), 'like', $name)->count();
                if ($dbCountSkill > 0) {
                    throw new  Exception($name . ' ' .trans('messages.skill.skill_exist'), 1);
                }
                $skill = new Skill;
                $skill->name = $skills['skillName'][$i];
                $skill->short_name = $skills['skillName'][$i];
                $skill->skill_category_id = $categoryId;
                $skill->user_id = $userId;
                $skill->save();

                $userSkill = new UserSkill;
                $userSkill->user_id = $userId;
                $userSkill->skill_id = $skill->id;
                $userSkill->year = $skills['skillYear'][$i];
                $userSkill->save();
            }
        }
    }
}
