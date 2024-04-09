@extends('game.layouts.app')

@section('content')
    @php
        $users = [];
        foreach ($events as $event) {
            $users[] = [
                'fillStyle' => $event->color_session,
                'text' => (string)$event->sesion_code
            ];
        }
        $num = count($events);
    @endphp
    <div id="user"
        align="center"
        data-id="{{json_encode($users)}}"
        data-num="{{$num}}"
        data-task-id="{{$task_id}}"
        data-type="session">
        <h1 class="title-event">Vòng quay may mắn</h1>
        <div id="aniWin"></div>
        <div class="the_wheel">
            <div class="animate-charcter" style="width: 840px; height: 840px; background: linear-gradient(to top right,#08aeea 0%,#b721ff 100%); border-radius: 50%; margin: 0 auto;">
                <canvas id="canvas" width="800" height="800">
                    <p style="color: white" align="center">Not support</p>
                </canvas>
            </div>
            <img id="prizePointer" src="https://d37c8ertxcodlq.cloudfront.net/others/basic_pointer.png" alt="V" />
            <div id="pinVn" alt="Spin">QUAY</div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center bg-modal">
          <div class="modal-body text-center">
                <img src="https://acegif.com/wp-content/uploads/gift-5.gif" style="width: 50%; border-radius: 120px;">
                <h2 id="number-win" class="text-center fs-50 pt-4 animate-charcter"></h2>
                <button id="nextUser" type="button" class="btn btn-primary m-4" style="background: #17a8e3; color: #fff;background: #17a8e3; color: #fff;padding: 20px 55px; border-radius: 30px;font-size: 20px;}">Tiếp theo</button>
          </div>
        </div>
      </div>
    </div>
@endsection
