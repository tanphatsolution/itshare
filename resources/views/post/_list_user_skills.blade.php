<ul class="user-skill-list">
@foreach($userSkills as $index => $userSkill)
    <li class="fake-link user-skill">
        <span>{{{ $userSkill->skill }}}</span>
        <span class="category">&nbsp;({{{ $userSkill->category }}})</span>
        <span>-&nbsp;{{{  round($userSkill->year, 2) }}}&nbsp;{{{\App\Services\HelperService::myPluralizer(trans('messages.skill.year'), $userSkill->year, $lang)}}}</span>
    </li>
@endforeach
</ul>
