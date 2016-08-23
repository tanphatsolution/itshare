@foreach($skills as $skill)
    @if (App\Services\UserSkillService::skillDisplayable($skill, $currentUser))
        <?php $k = null ?>
        @foreach($userSkills as $userSkill)
            @if ($skill->id == $userSkill->skillId)
                <?php $k = $userSkill->year ?>
            @endif
        @endforeach
        <div class="sub-skill-element fader zeroOpacity" id="skill{{ $skill->id }}">
            <div class="input-group">
                <span class="skill-name input-group-addon col-md-4.5"><label for="skills[{{ $skill->id }}]">{{{ $skill->name }}}</label></span>
                <select class="form-control skill-year col-md-1" name="skills[{{ $skill->id }}]">
                    <option value="{{{ isset($k) ? App\Data\Blog\UserSkill::SKILL_DELETE_FLAG : '' }}}">{{ trans('labels.year_label') }}</option>
                    @for ($i = 0; $i < 10; $i += App\Data\Blog\UserSkill::STEP_SKILL_YEAR)
                        <option value="{{ $i }}" @if (isset($k) && $i == $k) selected @endif >{{ $i }}</option>
                    @endfor
                </select>
            </div>
            @if (App\Services\UserSkillService::skillEditable($skill, $currentUser))
            <span class="btn btn-danger inline-table remove-skill"><i class="fa fa-times"></i></span>
            @endif
        </div>
    @endif
@endforeach