@extends('emails.layouts.default')
@section('main')
    {{ Form::open(['action' => 'FeedbacksController@postReply','class' => 'form']) }}
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                    @if (isset($data['feedback']) && $data['feedback'] != '')
                    <tr>
                        <td align="left" bgcolor="#ffffff" style="padding: 4px 0 3px 0;">
                        <blockquote style="background: #f9f9f9; border-left: 10px solid #ccc; margin: 1.5em 10px; padding: 0.5em 10px; quotes: '\201C''\201D''\2018''\2019';">
                        {{ $data['feedback'] }}
                        <p></p>
                        </blockquote>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 4px 3px 4px 3px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding: 0 0 5px 0;">
                                        {{ Form::text('greeting', trans('feedbacks.reply_mail.greeting').$data['name'], ['style' => 'border: 0; background-color: #ffffff', 'disabled']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            {{ Form::textarea('reply', $data['reply'], ['class' => 'form-control resize-none', 'style' => 'border: 0; resize: none; background-color: #ffffff', 'disabled', ]) }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {{ Form::close()  }}
@stop
