<div class="module user-ranking gray-box">
    <h4 class="module-title">User Ranking</h4>
    <div class="module-content">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#thisWeek" aria-controls="thisWeek" role="tab" data-toggle="tab">This week</a>
            </li>
            <li>
                <a onclick="home.onClickTabRank('thisMonth')" href="#thisMonth" aria-controls="thisMonth" role="tab" class="month"
                   data-toggle="tab">This month</a>
            </li>
            <li>
                <a onclick="home.onClickTabRank('Overall')" href="#Overall" aria-controls="Overall" role="tab" class="over"
                   data-toggle="tab">Overall</a>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="thisWeek">
                <ul class="user-list">
                    @include('faq.sidebarleft.list_user_ranking')
                </ul>
                <a href="#" class="ranking-page">Go to ranking page</a>
            </div>
            <div role="tabpanel" class="tab-pane" id="thisMonth">
                <ul class="user-list thisMonth">
                    <li class="loading">Loading ...</li>
                </ul>
                <a href="#" class="ranking-page">Go to ranking page</a>
            </div>
            <div role="tabpanel" class="tab-pane" id="Overall">
                <ul class="user-list Overall">
                    <li class="loading">Loading ...</li>
                </ul>
                <a href="#" class="ranking-page">Go to ranking page</a>
            </div>
        </div>
    </div>
</div>