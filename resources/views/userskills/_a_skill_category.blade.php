@foreach($skillCategories as $category)
    @if (App\Services\UserSkillService::skillCategoryDisplayable($category, $currentUser))
        <div class="row skill-cointainer" id="category{{ $category->id }}">
            <div class="col-md-4 skill-category-cointainer">
                <div class="skill-category-wrapper">
                    <input type="text" class="category-skill" value="{{{ $category->name }}}" disabled/>
                    @if (App\Services\UserSkillService::skillCategoryEditable($category, $currentUser))
                    <span class="btn btn-danger remove-skill-category pull-right" id="{{ $category->id }}"
                            data-message="{{ trans('messages.skill.warning_message') }}"
                            data-labels="{{ trans('messages.skill.warning_title') }}"
                            data-confirm="{{ trans('messages.skill.warning_confirm') }}">
                        <i class="fa fa-times"></i>
                    </span>
                    @endif
                    <span class="btn btn-info add-sub-skill pull-right" id="{{ $category->id }}"><i class="fa fa-plus" id="{{ $category->id }}"></i></span>
                </div>
            </div>
            <div class="col-md-8 skill-list-cointainer hidden" id="skillListCointainer{{ $category->id }}">
                <div class="form form-inline skill-list" id="skillList{{ $category->id }}">
                    @include('userskills._a_skill', ['skills' => $category->skills])
                </div>
                <div class="sub-skill-element">
                    {{ Form::label('skill', trans('labels.add_sub_skill'), ['class' => 'label-add-more']) }}
                    <span class="btn btn-info add-new-sub-skill" id="add-new-sub-skill"><i class="fa fa-plus"></i></span>
                </div>
            </div>
        </div>
    @endif
@endforeach
<div class="row skill-cointainer">
    {{ Form::label('skill', trans('labels.add_skill_category'), ['class' => 'label-add-more']) }}
    <span class="btn btn-info add-skill-category" id="add-skill-category"><i class="fa fa-plus"></i></span>
</div>
