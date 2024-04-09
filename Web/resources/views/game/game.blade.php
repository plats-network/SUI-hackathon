<html>
    <head>
      <title>Event | Vòng quay may mắn</title>
      {{-- Favicon --}}
      <link rel="apple-touch-icon" sizes="180x180" href="{{url('/')}}/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="{{url('/')}}/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="{{url('/')}}/favicon-16x16.png">
      <link rel="manifest" href="{{url('/')}}/site.webmanifest">
      <meta name="csrf-token" content="{{ csrf_token() }}"/>
      {{-- End Favicon --}}
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      <script type="text/javascript" src="{{url('/')}}/js/Winwheel.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

      <style type="text/css">
          .output {
            margin: 20px;
            padding: 20px;
            background: gray;
            border-radius: 10px;
            font-size: 20px;
            width: 80px;
            color: white;
            float: left;
        }

        body {
            background-size: cover;
        }
        .start {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            box-shadow: 0 0 60px rgba(0, 255, 203, 0.64);
        }

        .item {
            width: 200px;
            height: 200px;
            background-color: #187fe2;
            color: #fff;
            font-size: 50px;
            border-radius: 50%;
            padding-top: 62px;
        }
        .btn-stop {
            background-color: red;
            color: #fff;
        }

        .btn:hover {color: #fff!important;}

        .btn-start {
            background-color: #187fe2;
            color: #fff;
            border: 2px solid red;
            box-shadow: 0 0 60px rgba(0, 255, 203, 0.64);
        }

        .logo {
            text-align: center;
            padding-top: 24px;
        }

        .btn-prize {
            background-color: #1fd1f9;
            background-image: linear-gradient(315deg, #1fd1f9 0%, #b621fe 74%);
            transform: scale(1.2) rotate(0);
            padding-top: 40px;
            font-size: 80px;
            -webkit-transition: all 1s ease;
            -moz-transition: all 1s ease;
            -o-transition: all 1s ease;
            -ms-transition: all 1s ease;
            transition: all 1s ease;
        }

        .locked {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: #1e4797;
          opacity: 0.86;
        }

        .text-locked {
          position: absolute;
          left: 30%;
          top: 35%;
          font-size: 160px;
          z-index: 10;
          padding: 20px 100px;
          color: #fff;
        }
      </style>

      @vite(['resources/sass/game.scss'])
    </head>

    <body
        style="background-image: url({{url('/')}}/game/{{$img}})"
        id="game"
        data-numbers="{{json_encode($numbers)}}"
        data-soxo="{{url('/')}}/game/soxo.mp3"
        data-votay="{{url('/')}}/game/votay.mp3"
        data-num="{{$num}}"
        data-code="{{$code}}"
        data-url="{{route('game.updateResult')}}"
        data-env="{{env('APP_ENV') == 'local' ? false : true}}">

        <div class="container-fluid {{$is_locked ? '' : 'locked'}}">
            @if (!$is_locked)
              <p class="text-locked">Locked</p>
            @endif

            <div class="logo">
                <a href="#">
                    <img class="logo-img" src="{{url('/')}}/game/logo-game.svg">
                </a>
            </div>

            <div style="margin: 10% 0 50px; display: block;">
                <div class="row text-center">
                    @if ($num == 1)
                        <div class="col-5">&nbsp;</div>
                        <div class="col-2">
                            <div class="item output" id="output0">--</div>
                        </div>
                        <div class="col-5">&nbsp;</div>
                    @elseif($num == 2)
                        <div class="col-3">&nbsp;</div>
                        @for($a = 0; $a < $num; $a++)
                            <div class="col-3 text-center">
                                <div class="item output" id="output{{$a}}" style="margin-left: 60px;">--</div>
                            </div>
                        @endfor
                        <div class="col-3">&nbsp;</div>
                    @elseif($num == 3)
                        <div class="col-2">&nbsp;</div>
                        @for($a = 0; $a < $num; $a++)
                            <div class="col-3 text-center">
                                <div class="item output" id="output{{$a}}" style="margin-left: 60px;">--</div>
                            </div>
                        @endfor
                        <div class="col-1">&nbsp;</div>
                    @elseif($num == 4)
                        @for($a = 0; $a < $num; $a++)
                            <div class="col-3 text-center">
                                <div class="item output" id="output{{$a}}" style="margin-left: 80px;">--</div>
                            </div>
                        @endfor
                    @else
                        <div class="col-1">&nbsp;</div>
                        @for($a = 0; $a < $num; $a++)
                            <div class="col-2">
                                <div class="item output" id="output{{$a}}">--</div>
                            </div>
                        @endfor
                        <div class="col-1">&nbsp;</div>
                    @endif
                </div>
            </div>
            <div style="margin: 0 auto;" class="text-center">
                <button class="btn btn-start start" id="{{$is_locked ? 'start' : ''}}">Start</button>
            </div>
        </div>

        <script type="text/javascript">
            var env = $('#game').data('env'),
                numbers = $('#game').data('numbers'),
                soxo = $('#game').data('soxo'),
                url = $('#game').data('url'),
                code = $('#game').data('code'),
                votaymp3 = $('#game').data('votay');
            let audio = new Audio(soxo);
            let audio2 = new Audio(votaymp3);
            var ids = [];
            var l = $('#game').data('num');

            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

            // Lock
            if (env) {
                document.addEventListener('keydown', function() {
                if (event.keyCode == 123) {
                  return false;
                } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
                  return false;
                } else if (event.ctrlKey && event.keyCode == 85) {
                  return false;
                }
              }, false);

              if (document.addEventListener) {
                document.addEventListener('contextmenu', function(e) {
                  e.preventDefault();
                }, false);
              } else {
                document.attachEvent('oncontextmenu', function() {
                  window.event.returnValue = false;
                });
              }
            }
            // End Lock

            function playSound() {
                audio.currentTime = 0;
                audio.play();
            }
            function stropSound() {
                audio.pause();
            }
            function votay() {
                audio2.pause();
                audio2.currentTime = 0;
                audio2.play();
            }

            function phaohoa() {
                var duration = 30 * 1000;
                var animationEnd = Date.now() + duration;
                var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

                function randomInRange(min, max) {
                  return Math.random() * (max - min) + min;
                }

                var interval = setInterval(function() {
                  var timeLeft = animationEnd - Date.now();

                  if (timeLeft <= 0) {
                    return clearInterval(interval);
                  }

                  var particleCount = 50 * (timeLeft / duration);
                  confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                  confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
                }, 100);
            }

            function generateNumber(index) {
                if(index >= l) {
                    $('#start').removeClass('btn-danger').addClass('btn-stop b').attr('disabled', false).attr('id', 'stop').html('Reset');
                    stropSound();
                    votay();
                    phaohoa();

                    $.ajax({
                      type: 'post',
                      url: url,
                      data: {
                        _token: "{{csrf_token()}}",
                        code: code,
                        ids: ids
                      },
                      success:function(data){
                        $('tbody').html(data);
                      }
                    });
                    return;
                } else {
                  var desired = numbers[index];
                  if (l == 1) {
                    var duration = 20000;
                  } else if(l == 2) {
                    var duration = 15000;
                  } else if(l == 3) {
                    var duration = 12000;
                  } else if(l == 4) {
                    var duration = 9000;
                  } else {
                    var duration = 10000;
                  }

                  var output = $('#output' + index); // Start ID with letter
                  var started = new Date().getTime();
                  animationTimer = setInterval(function() {
                    if (output.text().trim() === desired || new Date().getTime() - started > duration) {
                      clearInterval(animationTimer);
                      var rnd = random_item(numbers);
                      ids.push(rnd);
                      output.addClass('btn-prize').text(rnd);
                      generateNumber(index + 1);

                      numbers = numbers.filter(item => !ids.includes(item))
                    } else {
                      output.text(random_item(numbers));
                    }
                  }, 200);
                }
            }

            function random_item(items) {
                return items[Math.floor(Math.random()*items.length)];
            }

            $(document).on('click', '#start', function (e) {
                $(this).removeClass('btn-start').addClass('btn-danger').attr('disabled', true).html('Running');
                generateNumber(0);
                playSound();
            });

            $(document).on('click', '.b', function (e) {
                location.reload();
            });
      </script>
    </body>
</html>
