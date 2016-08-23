  <!-- Modal -->
<div class="modal select-language-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span class="sr-only">{{ trans('labels.modal.close') }}</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('labels.markdown.help') }}</h4>
            </div>
            <div class="modal-body modal-body-markdown-help">
                <table>
                    <thead>
                        <tr>
                            <th>{{ trans('labels.markdown.result') }}</th>
                            <th>{{ trans('labels.modal.markdown') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>{{ trans('labels.modal.font_bold') }}</b></td>
                            <td>**{{ trans('labels.modal.text') }}**</td>
                        </tr>
                        <tr>
                            <td><i>{{ trans('labels.modal.font_emphasize') }}</i></td>
                            <td>*{{ trans('labels.modal.text') }}*</td>
                        </tr>
                        <tr class="highlight">
                            <td><code>{{ trans('labels.modal.inline_code') }}</code></td>
                            <td>`{{ trans('labels.code') }}`</td>
                        </tr>
                        <tr>
                            <td><a href="#">{{ trans('labels.modal.link') }}</a></td>
                            <td>[{{ trans('labels.modal.link_title') }}](http://~)</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.image') }}</td>
                            <td>![{{ trans('labels.modal.alt') }}](http://~)</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.image_title_and_size') }}</td>
                            <td>![{{ trans('labels.modal.alt') }}](http://~ "{{ trans('labels.title') }}" =WxH)</td>
                        </tr>
                        <tr>
                            <td><h4>{{ trans('labels.heading') }} (h1~h6)</h4></td>
                            <td># {{ trans('labels.modal.text') }}, ## {{ trans('labels.modal.text') }}, ### {{ trans('labels.modal.text_more') }}</td>
                        </tr>
                        <tr>
                            <td><li>{{ trans('labels.modal.list') }}</li></td>
                            <td>* {{ trans('labels.modal.item') }}</td>
                        </tr>
                        <tr>
                            <td><li style="list-style-type: decimal;">{{ trans('labels.modal.decimal_list') }}</li></td>
                            <td>1. {{ trans('labels.modal.item') }}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.horizontal_rules') }}</td>
                            <td>* * *</td>
                        </tr>
                        <tr>
                            <td><blockquote style="margin: 0;">{{ trans('labels.modal.blockquotes') }}</blockquote></td>
                            <td>&gt; {{ trans('labels.modal.text') }}</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.escape_markdown') }}</td>
                            <td>\</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.youtube_embed') }}</td>
                            <td>[youtube-400x500](http://~|{{ trans('labels.modal.youtube_id') }})</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.vimeo_embed') }}</td>
                            <td>[vimeo-400x500](http://~|{{ trans('labels.modal.vimeo_id') }})</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.slideshare_embed') }}</td>
                            <td>[slideshare-400x500]({{ trans('labels.modal.slideshare_id') }})</td>
                        </tr>
                        <tr>
                            <td>{{ trans('labels.modal.textile_embed') }}</td>
                            <td>
                                ```{{ trans('labels.modal.textiletohtml') }}<br/>
                                <i>{{ trans('labels.modal.textile_code_here') }}</i><br/>
                                <i>{{ trans('labels.modal.textile_more_info') }}</i><br/>
                                ```
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>