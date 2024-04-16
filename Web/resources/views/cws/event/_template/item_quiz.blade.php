<div class="mb-3 row itemQuizDetail" id="itemQuiz{{$indexImageItem}}">
    <input type="hidden" name="quiz[{{$indexImageItem}}][is_delete]" id="quizFlagDelete{{$indexImageItem}}" value="0">
    <div class="row">
        <div class="col-lg-7">
            <div class="mb-3">
                <label for="quiz[{{$indexImageItem}}][name]" class="form-label">Question {{$getInc}}</label>
                <input
                    type="text"
                    class="form-control"
                    id="quiz[{{$indexImageItem}}][name]"
                    placeholder="Question {{$getInc}}"
                    name="quiz[{{$indexImageItem}}][name]">
            </div>
        </div>
        <div class="col-lg-2">
            <div class="mb-3">
                <label for="quiz[{{$indexImageItem}}][time_quiz]" class="form-label">Time</label>
                <input
                    type="number"
                    class="form-control"
                    id="quiz[{{$indexImageItem}}][time_quiz]"
                    min="0"
                    placeholder="Time"
                    name="quiz[{{$indexImageItem}}][time_quiz]"
                    value="">
            </div>
        </div>
        <div class="col-lg-1">
            <div class="mb-3">
                <label for="basicpill-expiration-input"
                       class="form-label">Order</label>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    id="quiz[{{$indexImageItem}}][order]"
                    placeholder="Order" name="quiz[{{$indexImageItem}}][order]"
                    value="{{$getInc + 1}}">
            </div>
        </div>
        <div class="col-lg-2">
            <label for="quiz[{{$indexImageItem}}][status]"
                   class="form-label">&nbsp;</label>
            <div class="form-check form-switch">
                <input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    id="quiz[{{$indexImageItem}}][status]"
                    name="quiz[{{$indexImageItem}}][status]">
                <label class="form-check-label" for="flexSwitchCheckChecked">Status</label>
            </div>
        </div>
    </div>
    <br>
    {{--QuizAnswer--}}
    @for($index2 = 0; $index2 <= 3; $index2++)
        <div class="row">
            <div class="mb-3 row offset-md-1">
                <label for="inputPassword" class="col-sm-2 col-form-label">Answer {{$index2 +1}}</label>
                <div class="col-sm-7">
                    <input
                        type="text"
                        class="form-control"
                        id="quiz[{{$indexImageItem}}][detail][{{$index2}}][name]"
                        placeholder="Answer {{$index2 +1}}"
                        name="quiz[{{$indexImageItem}}][detail][{{$index2}}][name]"
                        value="">
                </div>
                <div class="col-sm-2">
                    <div class="form-check mt-2">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            id="{{$indexImageItem+$index2}}"
                            name="quiz[{{$indexImageItem}}][detail][{{$index2}}][status]">
                        <label class="form-check-label" for="{{$indexImageItem+$index2}}">
                            Option
                        </label>
                    </div>
                </div>
            </div>
        </div>
    @endfor

    {{--Delete Item--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3 d-flex flex-row-reverse">
                <label for="basicpill-expiration-input" class="form-label">&nbsp;</label>
                <button
                    type="button"
                    data-id="{{$indexImageItem}}"
                    class="btnDeleteImageQuiz btn btn-danger btn-rounded waves-effect waves-light mb-2 me-2">
                        <i class="mdi mdi-delete me-1"></i>
                    Delete</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("input:checkbox").click(function() {
        if ($(this).is(":checked")) {
            var group = "input:checkbox[name='" + $(this).attr("name") + "']";
            console.log(group);
            $(group).prop("checked", false);
            $(this).prop("checked", true);
        } else {
            $(this).prop("checked", false);
        }
    });
</script>
