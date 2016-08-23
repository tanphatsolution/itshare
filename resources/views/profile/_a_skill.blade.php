<div class="skill-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
    <div class="input-group col-sm-4">
        <span class="input-group-addon">{{ Form::label('skill_name', trans('messages.skill.name'), ['class' =>'skill_name']) }}</span>
        {{ Form::text('skill_name[]', $skill->name, ['class' =>'form-control skill-input', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'maxlength' => 20, 'placeholder' => trans('messages.skill.name_placeholder')]) }}
    </div>
    <div class="input-group col-sm-3">
        <span class="input-group-addon">{{ Form::label('skill_year', trans('messages.skill.year'), ['class' =>'skill_year']) }}</span>
        {{ Form::select('skill_year[]', App\Services\ProfileService::getSkillYearOptions(), $skill->year, ['class' => 'form-control']) }}
    </div>
    <button type="button" class="btn btn-danger inline-table remove-skill"><i class="fa fa-times"></i></button>
</div>