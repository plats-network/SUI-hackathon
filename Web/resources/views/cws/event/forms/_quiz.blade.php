<div id="tabwizard5" class="wizard-tab">
    <div>
        <div class="text-center mb-4">
            <h5>Quiz</h5>
            <p class="card-title-desc text-danger">
                - Trong sự kiện bạn tổ chức nếu có quiz thì vui lòng tạo quiz
                <br>
                - Nếu không có quiz bạn ấn "Save" để bỏ qua bước tạo quiz
            </p>
        </div>
        <div>
            <div class="listQuiz" id="listRowQuiz">
                @foreach($quiz as $k => $itemQuiz)
                    <div class="mb-3 row itemQuizDetail" id="itemQuiz{{$itemQuiz->id}}">
                        <input type="hidden" name="quiz[{{$itemQuiz->id}}][id]" value="{{$itemQuiz->id}}">
                        <input type="hidden" name="quiz[{{$itemQuiz->id}}][task_id]" value="{{$event->id}}">
                        <input type="hidden" name="quiz[{{$itemQuiz->id}}][is_delete]" id="quizFlagDelete{{$itemQuiz->id}}" value="0">

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="mb-3">
                                    <label
                                        for="basicpill-expiration-input"
                                        class="form-label">Question {{$loop->index +1}}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="quiz[{{$itemQuiz->id}}][name]"
                                        placeholder="Question {{$loop->index +1}}"
                                        name="quiz[{{$itemQuiz->id}}][name]"
                                        value="{{$itemQuiz->name}}">

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label
                                        for="basicpill-expiration-input"
                                        class="form-label">Time</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="quiz[{{$itemQuiz->id}}][time_quiz]"
                                        placeholder="Time"
                                        name="quiz[{{$itemQuiz->id}}][time_quiz]"
                                        value="{{$itemQuiz->time_quiz}}">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="mb-3">
                                    <label
                                        for="basicpill-expiration-input"
                                        class="form-label">Order</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="quiz[{{$itemQuiz->id}}][order]"
                                        placeholder="Order"
                                        name="quiz[{{$itemQuiz->id}}][order]"
                                        value="{{$itemQuiz->order}}">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label
                                    for="basicpill-expiration-input"
                                    class="form-label">&nbsp;</label>
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        id="quiz[{{$itemQuiz->id}}][status]"
                                        name="quiz[{{$itemQuiz->id}}][status]" @if($itemQuiz->status == 1) checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Status</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        {{--QuizAnswer--}}
                        @foreach($itemQuiz->detail as $key => $itemQuizAnswer)
                            <input
                                type="hidden"
                                name="quiz[{{$itemQuiz->id}}][detail][{{$key}}][id]"
                                value="{{$itemQuizAnswer->id}}">
                            <input
                                type="hidden"
                                name="quiz[{{$itemQuiz->id}}][detail][{{$key}}][quiz_id]"
                                value="{{$itemQuizAnswer->quiz_id}}">
                            <div class="row">
                                <div class="mb-3 row offset-md-1">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Answer {{$key +1}}</label>
                                    <div class="col-sm-7">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="quiz[{{$itemQuiz->id}}][detail][{{$key}}][name]"
                                            placeholder="Answer {{$key +1}}"
                                            name="quiz[{{$itemQuiz->id}}][detail][{{$key}}][name]"
                                            value="{{$itemQuizAnswer->name}}">
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-check mt-2">
                                            <input
                                                class="form-check-input checkOptionQuiz"
                                                type="checkbox"
                                                value="1"
                                                id="aws_{{$itemQuiz}}_{{$key}}"
                                                name="quiz[{{$itemQuiz->id}}][detail][{{$key}}][status]" @if($itemQuizAnswer->status == 1) checked @endif
                                                @if($isPreview) redonly @endif
                                            >
                                            <label
                                                class="form-check-label"
                                                for="aws_{{$itemQuiz}}_{{$key}}">
                                                Option
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if (!$isPreview)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3 d-flex flex-row-reverse">
                                        <label
                                            for="basicpill-expiration-input"
                                            class="form-label">&nbsp;</label>
                                        <button
                                            type="button"
                                            data-id="{{$itemQuiz->id}}"
                                            class="btnDeleteImageQuiz btn btn-danger btn-rounded waves-effect waves-light mb-2 me-2"
                                            onclick="deleteItemQuiz({{$itemQuiz->id}})">
                                            <i class="mdi mdi-delete me-1"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="row mt-3 @if($isPreview) invisible @endif">
                <div class="d-flex flex-row-reverse">
                    <div class="p-2">
                        <button
                            id="btnAddItemQuiz"
                            type="button"
                            class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                            <i class="mdi mdi-plus me-1"></i> Add More</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
