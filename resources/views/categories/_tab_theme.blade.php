<ul class="list-title">
    <li>
        <a class="{{ !isset($tab) || $tab == '' ? 'selected' : '' }}" href="{{ URL::to('theme/' . $lang . '/' . $category['theme_subject_short_name'] . '/' . $category['short_name']) }}" id="new">
            {{ trans('categories.new_posts') }}
        </a>
    </li>
    <li>
        <a class="{{ isset($tab) && $tab == 'recent' ? 'selected' : '' }}" href="{{ URL::to('theme/' . $lang . '/' . $category['theme_subject_short_name'] . '/' . $category['short_name'] .'/recent') }}" id="recent">
            {{ trans('categories.recently_stocked_posts') }}
        </a>
    </li>
    <li>
        <a class="{{ isset($tab) && $tab == 'top' ? 'selected' : '' }}" href="{{ URL::to('theme/' . $lang . '/' . $category['theme_subject_short_name'] . '/' . $category['short_name'] .'/top') }}" id="top">
            {{ trans('categories.top_posts') }}
        </a>
    </li>
    <li>
        <a class="{{ isset($tab) && $tab == 'helpful' ? 'selected' : '' }}" href="{{ URL::to('theme/' . $lang . '/' . $category['theme_subject_short_name'] . '/' . $category['short_name'] .'/helpful') }}" id="helpful">
            {{ trans('categories.helpful_posts') }}
        </a>
    </li>
</ul>
