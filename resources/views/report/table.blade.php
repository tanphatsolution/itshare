@if ($reports->count() > 0 )
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th id="select-all" class="select-all">{{ trans('messages.report.select') }}</th>
            <th>{{ trans('messages.report.reporter') }}</th>
            <th>{{ trans('messages.report.post') }}</th>
            <th>{{ trans('messages.report.type') }}</th>
            <th>{{ trans('messages.report.status') }}</th>
            <th>{{ trans('messages.report.action') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reports as $report)
            @if (!is_null($report->post) && !is_null($report->user))
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ Form::checkbox("selected[$report->id]", '1') }}</td>
                    <td>{{ link_to_user($report->user->username) }}</td>
                    <td>
                        @if (!is_null($report->post->deleted_at))
                            <a href="javascript:void(0);"
                               onclick="showDeletedMessage('{{ trans('messages.report.post_been_deleted') }}')">{{ $report->post->title }}</a>
                        @else
                            <a href="{{ route('post.detail', [
                                     'username' => $report->post->user->username,
                                    'encryptedId' => $report->post->encryptedId
                                ]) }}">{{{ $report->post->title }}}</a>
                        @endif
                    </td>
                    <td>{{ \App\Services\ReportService::getTypeLabel($report->type) }}</td>
                    <td>{{ \App\Services\ReportService::getStatusLabel($report->status) }}</td>
                    <td>
                        <a href="#" data-id="{{ $report->id }}"
                           data-message="{{ trans('messages.report.confirm_delete', ['id' => $report->id]) }}"
                           data-yes="{{ trans('buttons.yes') }}" data-no="{{ trans('buttons.no') }}"
                           class="action-delete"> {{ trans('buttons.delete') }}</a>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
@endif
