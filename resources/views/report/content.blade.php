@if ($reports->count() > 0 )
    @include('report.table', ['reports' => $reports])
@else
    {{ trans('messages.report.no_report') }}
@endif
