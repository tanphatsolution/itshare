@extends('emails.layouts.default')
@section('main')
    {{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
    {{ Form::open(['action' => 'FeedbacksController@postReply','class' => 'form']) }}
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                    @if (isset($feedback) && !empty($feedback))
                    <tr>
                        <td align="left" bgcolor="#ffffff" style="padding: 4px 0 3px 0;">
                            <blockquote style="background: #f9f9f9; border-left: 10px solid #ccc; margin: 1.5em 10px; padding: 0.5em 10px; quotes: '\201C''\201D''\2018''\2019';">
                                <p style="margin-top: 0px; margin-bottom: 0px;">
                                    <span style="font-weight: bold;">{{ $title }}</span><br/>
                                    <span>{{ $feedback }}</span>
                                </p>
                            </blockquote>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 4px 3px 4px 3px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            {{ $name }},
                                            <br/><br/>
                                            {{ $reply }}
                                            <br/><br/>
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
@stop
