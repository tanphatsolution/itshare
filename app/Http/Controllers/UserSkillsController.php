<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use DB;

use App\Data\Blog\SkillCategory;
use App\Data\Blog\UserSkill;
use App\Data\Blog\Skill;

use App\Services\UserSkillService;

class UserSkillsController extends BaseController
{

    /**
     * Instantiate a new ProfilesController instance.
     */
    public function __construct()
    {
        parent::__construct();
        // Authentication filter
        $this->middleware('auth', [
            'only' => [
                'create',
                'store'
            ]
        ]);
    }

    public function create()
    {
        $this->viewData['userSkills'] = $this->currentUser->skills;
        $this->viewData['skillCategories'] = SkillCategory::with('skills')->get();
        $this->viewData['title'] = trans('titles.skill_setting');
        return View::make('userskills.create', $this->viewData);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            if ($type == 'category') {
                $cnt = $request->get('cnt');
                return View::make('userskills._new_skill_category', ['cnt' => $cnt])->render();
            } elseif ($type == 'skill') {
                $cnt = $request->get('cnt');
                return View::make('userskills._new_sub_skill', ['cnt' => $cnt])->render();
            } elseif ($type == 'categorySkill') {
                $categoryId = $request->get('categoryId');
                return View::make('userskills._new_category_sub_skill', ['categoryId' => $categoryId])->render();
            }
            return json_encode(false);
        }
        $input = $request->all();
        $savedSkills = isset($input['skills']) ? $input['skills'] : [];
        $savedSkills = array_filter($savedSkills, 'is_numeric');
        DB::beginTransaction();
        try {
            foreach($savedSkills as $skillId => $year) {
            if (!is_null($year)) {
                    UserSkillService::saveSubSkill([$skillId, $year], $this->currentUser->id);
                }
            }

            $savedSkillSets = isset($input['skillSet']) ? $input['skillSet'] : null;
            if (!is_null($savedSkillSets)) {
                UserSkillService::saveSkillSet($savedSkillSets, $this->currentUser->id);
            }

            $removedCategories = isset($input['removedCategories']) ? $input['removedCategories'] : null;
            if (!is_null($removedCategories)) {
                SkillCategory::destroy($removedCategories);
                $removedSkills = Skill::whereIn('skill_category_id', $removedCategories);
                UserSkill::whereIn('skill_id', $removedSkills->select('id')->get()->toArray())->delete();
                $removedSkills->delete();
            }

            $categorySkills = isset($input['categorySkills']) ? $input['categorySkills'] : null;
            if (!is_null($categorySkills)) {
                UserSkillService::saveCategorySkills($categorySkills, $this->currentUser->id);
            }
            $removedSkills = isset($input['removedSkills']) ? $input['removedSkills'] : null;
            if (!is_null($removedSkills)) {
                Skill::destroy($removedSkills);
                UserSkill::whereIn('skill_id', $removedSkills)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
                return \Redirect::back()->with('err', $e->getMessage());
        }

        return \Redirect::back()
            ->with('message', trans('messages.profile.update_success'));
    }
}
