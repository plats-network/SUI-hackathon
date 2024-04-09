@extends('web.layouts.event_app')

@section('content')
    <section class="quiz">
        <div class="item">
            <h3 id="taskQ"
                data-url="{{route('web.jobEvent', ['id' => $task_code, 'type' => 0])}}"
                data-url2="{{route('job.getTravelGame', ['task_id' => $task_id])}}"
                data-num="{{$count}}">
                {{$detail->question}}
                <p class="text-danger">Vui lòng chọn 1 đáp án.</p>
            </h3>

            @if ($detail->a1)
                <div class="aws" data-id="{{$detail->id}}" data-result="{{$detail->is_a1 ? 1 : 0 }}">
                    <p>{{$detail->a1}}</p>
                </div>
            @endif
            @if ($detail->a2)
                <div class="aws" data-id="{{$detail->id}}" data-result="{{$detail->is_a2 ? 1 : 0}}">
                    <p>{{$detail->a2}}</p>
                </div>
            @endif
            @if ($detail->a3)
                <div class="aws" data-id="{{$detail->id}}" data-result="{{$detail->is_a3 ? 1 : 0}}">
                    <p>{{$detail->a3}}</p>
                </div>
            @endif
            @if ($detail->a4)
                <div class="aws" data-id="{{$detail->id}}" data-result="{{$detail->is_a4 ? 1 : 0}}">
                    <p>{{$detail->a4}}</p>
                </div>
            @endif
        </div>
    </section>
@endsection