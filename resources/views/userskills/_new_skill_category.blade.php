<div class="row skill-cointainer">
    <div class="col-md-4 skill-category-cointainer">
        <div class="skill-category-wrapper">
            <input type="text" class="category-skill" name="skillSet[{{ $cnt }}][newCategory]" value=""/>
            <span class="btn btn-danger remove-skill-category pull-right" 
                data-message="{{ trans('messages.skill.warning_message') }}"
                data-labels="{{ trans('messages.skill.warning_title') }}"
                data-confirm="{{ trans('messages.skill.warning_confirm') }}">
                <i class="fa fa-times"></i>
            </span>
            <span class="btn btn-info remove-sub-skill pull-right"><i class="fa fa-minus"></i></span>
        </div>
    </div>
    <div class="col-md-8 skill-list-cointainer">
        <div class="form form-inline skill-list">
            <div class="skill-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
                <div class="input-group col-sm-4.5">
                    <span class="input-group-addon">{{ Form::label('skill_name', trans('messages.skill.name'), ['class' =>'skill_name']) }}</span>
                    {{ Form::text('skillSet['. $cnt .'][newSkill][skillName][]', '', ['class' =>'form-control skill-input', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'maxlength' => 20, 'placeholder' => trans('messages.skill.name_placeholder')]) }}
                </div>
                <div class="input-group col-sm-3.5">
                    <span class="input-group-addon">{{ Form::label('skill_year', trans('messages.skill.year'), ['class' =>'skill_year']) }}</span>
                    {{ Form::select('skillSet['. $cnt .'][newSkill][skillYear][]', App\Services\ProfileService::getSkillYearOptions(), 0.5, ['class' => 'form-control']) }}
                </div>
                <span class="btn btn-danger inline-table remove-skill"><i class="fa fa-times"></i></span>
            </div>
        </div>
        <div class="sub-skill-element">
            {{ Form::label('skill', trans('labels.add_sub_skill'), ['class' => 'label-add-more']) }}
            <span class="btn btn-info add-new-sub-skill" id="add-new-sub-skill"><i class="fa fa-plus"></i></span>
        </div>
    </div>
</div>