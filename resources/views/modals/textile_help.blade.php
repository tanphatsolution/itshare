  <!-- Modal -->
<div class="modal select-language-modal fade" id="textileHelpModal" tabindex="-1" role="dialog" aria-labelledby="Textile Help" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only">{{ trans('labels.modal.close') }}</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('labels.textile.help') }}</h4>
            </div>
            <div class="modal-body modal-body-markdown-help">
                <p>{{ trans('labels.modal.textile_message') }}</p>
                <table>
                    <thead>
                        <tr>
                            <th>{{ trans('labels.textile.result') }}</th>
                            <th>{{ trans('labels.textile.title') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><h1>{{ trans('labels.modal.level_1_heading') }}</h1></td>
                            <td>h1. {{ trans('labels.modal.level_1_heading') }}</td>
                        </tr>
                        <tr>
                            <td><h2>{{ trans('labels.modal.level_2_heading') }}</h2></td>
                            <td>h2. {{ trans('labels.modal.level_2_heading') }}</td>
                        </tr>
                        <tr>
                            <td><h3>{{ trans('labels.modal.level_3_heading') }}</h3></td>
                            <td>h3. {{ trans('labels.modal.level_3_heading') }}</td>
                        </tr>
                        <tr>
                            <td><h4>{{ trans('labels.modal.level_4_heading') }}</h4></td>
                            <td>h4. {{ trans('labels.modal.level_4_heading') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <blockquote>
                                <p>{{ trans('labels.modal.blockquoted_text') }}</p>
                                </blockquote>
                            </td>
                            <td>bq. {{ trans('labels.modal.blockquoted_text') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <ol>
                                    <li>{{ trans('labels.modal.numbered_item_1') }}</li>
                                    <li>{{ trans('labels.modal.numbered_item_2') }}</li>
                                </ol>
                            </td>
                            <td>
                                # {{ trans('labels.modal.numbered_item_1') }}<br/>
                                # {{ trans('labels.modal.numbered_item_2') }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <li>{{ trans('labels.modal.bulleted_list_first') }}</li>
                                <li>{{ trans('labels.modal.bulleted_list_second') }}</li>
                            </td>
                            <td>
                                * {{ trans('labels.modal.bulleted_list_first') }}<br/>
                                * {{ trans('labels.modal.bulleted_list_second') }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><em>{{ trans('labels.modal.tag_emphasis') }}</em><br />
                                <strong>{{ trans('labels.modal.tag_strong') }}</strong><br />
                                <cite>{{ trans('labels.modal.tag_citation') }}</cite><br />
                                <del>{{ trans('labels.modal.tag_deleted_text') }}</del><br />
                                <ins>{{ trans('labels.modal.tag_inserted text') }}</ins><br />
                                <sup>{{ trans('labels.modal.tag_superscript') }}</sup><br />
                                <sub>{{ trans('labels.modal.tag_subscript') }}</sub><br />
                                <span>{{ trans('labels.modal.tag_span') }}</span></p>
                            </td>
                            <td>
                                _{{ trans('labels.modal.tag_emphasis') }}_<br/>
                                *{{ trans('labels.modal.tag_strong') }}*<br/>
                                ??{{ trans('labels.modal.tag_citation') }}??<br/>
                                -{{ trans('labels.modal.tag_deleted_text') }}-<br/>
                                +{{ trans('labels.modal.tag_inserted_text') }}+<br/>
                                ^{{ trans('labels.modal.tag_superscript') }}^<br/>
                                ~{{ trans('labels.modal.tag_subscript') }}~<br/>
                                %{{ trans('labels.modal.tag_span') }}%<br/>
                            </td>
                        </tr>
                        <tr>
                            <td><p><code>{{ trans('labels.modal.inline_code') }}</code></p></td>
                            <td>@inline {{ trans('labels.modal.code') }}}@</td>
                        </tr>
                        <tr>
                            <td><p style="color:red">{{ trans('labels.modal.paragrah') }}</p></td>
                            <td>p{color:red}. {{ trans('labels.modal.paragrah_css') }}</td>
                        </tr>
                        <tr>
                            <td><blockquote style="margin: 0;">{{ trans('labels.modal.blockquotes') }}</blockquote></td>
                            <td>&gt; {{ trans('labels.modal.text') }}</td>
                        </tr>
                        <tr>
                            <td><p style="text-align:left">{{ trans('labels.modal.paragrah_right_align') }}</p></td>
                            <td>p<. {{ trans('labels.modal.paragrah_right_align') }}</td>
                        </tr>
                        <tr>
                            <td><p style="text-align:right">{{ trans('labels.modal.paragrah_left_align') }}</p></td>
                            <td>p>. {{ trans('labels.modal.paragrah_left_align') }}</td>
                        </tr>
                        <tr>
                            <td><p style="text-align:center">{{ trans('labels.modal.paragrah_center_align') }}</p></td>
                            <td>p=. {{ trans('labels.modal.paragrah_center_align') }}</td>
                        </tr>
                        <tr>
                            <td><p style="text-align:justify">{{ trans('labels.modal.paragrah_justify_align') }}</p></td>
                            <td>p<>. {{ trans('labels.modal.paragrah_justify_align') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <th>{{ trans('labels.modal.table_head') }} </th>
                                        <th>{{ trans('labels.modal.table') }} </th>
                                        <th>{{ trans('labels.modal.table_row') }} </th>
                                    </tr>
                                    <tr>
                                        {{ trans('labels.modal.table_a_row_1') }}
                                    </tr>
                                    <tr>
                                        {{ trans('labels.modal.table_a_row_2') }}
                                    </tr>
                                </table>
                            </td>
                            <td>
                                |_. {{ trans('labels.modal.table_head') }} |_. {{ trans('labels.modal.table') }} |_. {{ trans('labels.modal.table_row') }} |<br/>
                                {{ trans('labels.modal.table_markdown_row_1') }}<br/>
                                {{ trans('labels.modal.table_markdown_row_2') }}
                            </td>
                        </tr>
                        <tr>
                            <td><p><img src="https://viblo.asia/img/logo-v2.png" alt="alt" /></p></td>
                            <td>!https://viblo.asia/img/logo-v2.png({{ trans('labels.modal.alt') }})!</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>