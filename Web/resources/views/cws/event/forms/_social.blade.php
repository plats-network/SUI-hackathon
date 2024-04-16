<div id="tabwizard4" class="wizard-tab">
    <div class="text-center mb-4">
        <h5>Social</h5>
        <p class="card-title-desc text-danger">
            - Trong sự kiện bạn tổ chức nếu có Social task thì vui lòng tạo Social
            <br>
            - Nếu không có Social task bạn ấn "Next" để bỏ qua bước tạo Social
        </p>
    </div>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Social</div>
                    <div class="card-body">
                        <div class="row">
                            <input
                                type="hidden"
                                name="task_event_socials[id]"
                                id="booths[id]"
                                value="{{$taskEventSocials->id}}">
                            <input
                                type="hidden"
                                name="task_event_socials[task_id]"
                                id="booths[task_id]"
                                value="{{$event->id}}">
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        @if( $taskEventSocials->is_comment) checked @endif
                                        type="checkbox"
                                        id="cmt"
                                        name="task_event_socials[is_comment]"
                                        value="1">
                                    <label class="form-check-label" for="cmt">Comment</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        @if( $taskEventSocials->is_like) checked @endif
                                        type="checkbox"
                                        id="is_like"
                                        name="task_event_socials[is_like]"
                                        value="1">
                                    <label class="form-check-label" for="is_like">like</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        @if( $taskEventSocials->is_retweet) checked @endif
                                        type="checkbox"
                                        id="retweet"
                                        name="task_event_socials[is_retweet]"
                                        value="1">
                                    <label class="form-check-label" for="retweet">Retweet</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        @if( $taskEventSocials->is_tweet) checked @endif
                                        type="checkbox"
                                        id="is_tweet"
                                        name="task_event_socials[is_tweet]"
                                        value="1">
                                    <label class="form-check-label" for="is_tweet">Tweet</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-cardno-input" class="form-label">Url</label>
                                        <input
                                            type="text"
                                            value="{{$taskEventSocials->url}}"
                                            class="form-control"
                                            placeholder="https://twitter.com/plats_network/status/1640186830644723712"
                                            id="task_event_socials[url]"
                                            name="task_event_socials[url]">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div id="tweetId" class="mb-3 {{$taskEventSocials->is_tweet ? '' : 'd-none'}}">
                                        <label for="tweet" class="form-label">Text</label>
                                        <input
                                            type="text"
                                            value="{{$taskEventSocials->text}}"
                                            class="form-control"
                                            placeholder="Event #Key #Text..."
                                            maxlenght="128"
                                            id="tweet"
                                            name="task_event_socials[text]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Discord</div>
                    <div class="card-body">
                        <input type="hidden" name="task_event_discords[id]" value="{{$taskEventDiscords->id}}">
                        <input type="hidden" name="task_event_discords[task_id]" value="{{$event->id}}">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Bot Token</label>
                                    <input type="text"
                                        class="form-control"
                                        placeholder="Bot Token"
                                        value="{{$taskEventDiscords->bot_token}}"
                                        name="task_event_discords[bot_token]">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Channel Id</label>
                                    <input type="text"
                                        class="form-control"
                                        placeholder="Channel Id"
                                        value="{{$taskEventDiscords->channel_id}}"
                                        name="task_event_discords[channel_id]">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Channel Url</label>
                                    <input type="text" class="form-control" placeholder="Channel Url" value="{{$taskEventDiscords->channel_url}}" name="task_event_discords[channel_url]" id="task_event_discords[channel_url]">
                                </div>
                            </div><!-- end col -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>