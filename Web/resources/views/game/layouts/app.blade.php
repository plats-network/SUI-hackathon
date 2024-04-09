<html>
    <head>
        <title>Event | Vòng quay may mắn</title>
        {{-- Favicon --}}
        <link rel="apple-touch-icon" sizes="180x180" href="{{url('/')}}/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{url('/')}}/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{url('/')}}/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        {{-- End Favicon --}}

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        {{-- <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Mountains+of+Christmas" /> --}}
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script type="text/javascript" src="{{url('/')}}/js/Winwheel.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
        @vite(['resources/sass/game.scss'])
    </head>

    <body class="bg">
        @yield('content')
        <script>
          document.addEventListener('keydown', function() {
            if (event.keyCode == 123) {
              alert("You Can not Do This!");
              return false;
            } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
              alert("You Can not Do This!");
              return false;
            } else if (event.ctrlKey && event.keyCode == 85) {
              alert("You Can not Do This!");
              return false;
            }
          }, false);
          
          if (document.addEventListener) {
            document.addEventListener('contextmenu', function(e) {
              alert("You Can not Do This!");
              e.preventDefault();
            }, false);
          } else {
            document.attachEvent('oncontextmenu', function() {
              alert("You Can not Do This!");
              window.event.returnValue = false;
            });
          }
          
            let users = $('#user').data('id'),
              taskId = $('#user').data('task-id'),
              type = $('#user').data('type'),
              num = $('#user').data('num');

            let theWheel = new Winwheel({
                'outerRadius': 400,
                'innerRadius': 10,
                'textFontSize': 25,
                'textOrientation': 'curved',
                'textAlignment': 'outer',
                'numSegments': num,
                'segments': users,
                'animation': {
                    'type': 'spinToStop',
                    'duration': 20,
                    'spins': 20,
                    'callbackFinished': alertPrize,
                    // 'callbackSound': playSound,
                    'soundTrigger': 'pin',
                },
                'pins': {
                    'number': num,
                    'fillStyle': 'silver',
                    'outerRadius': 4,
                }
            });

            $( "#pinVn" ).on( "click", function() {
              startSpin();
            });

            let audio = new Audio('https://d37c8ertxcodlq.cloudfront.net/others/soxo.mp3');
            function playSound() {
                audio.currentTime = 0;
                audio.play();
            }

            function stropSound() {
                audio.pause();
            }

            let audio2 = new Audio('https://d37c8ertxcodlq.cloudfront.net/others/Tieng-vo-tay-www_tiengdong_com.mp3');
            function votay()
            {
                audio2.pause();
                audio2.currentTime = 0;
                audio2.play();
            }

            let wheelPower = 0, wheelSpinning = false;
            function startSpin() {
                playSound();
                theWheel.animation.spins = 20;
                theWheel.startAnimation();
                wheelSpinning = true;
            }

            function resetWheel() {
                theWheel.stopAnimation(false);
                theWheel.rotationAngle = 0;
                theWheel.draw();
                wheelSpinning = false;
            }

            function alertPrize(indicatedSegment) {
                if (indicatedSegment.text == 'LOOSE TURN') {
                    alert('Sorry but you loose a turn.');
                } else if (indicatedSegment.text == 'BANKRUPT') {
                    alert('Oh no, you have gone BANKRUPT!');
                } else {
                    stropSound();

                    setTimeout(function() {
                        votay();
                    }, 800);

                    $('#aniWin').html('<div class="pyro"><div class="before"></div><div class="after"></div></div>');
                    $("#exampleModalCenter").modal('show');
                    $('#number-win').html(indicatedSegment.text);
                    if (type == 'session') {
                        $.get("/update-session/"+ taskId +"/" + indicatedSegment.text , function(data, status) {
                            console.log('ok');
                        });
                    } else {
                        $.get("/update-booth/"+ taskId +"/" + indicatedSegment.text , function(data, status) {
                            console.log('ok');
                        });
                    }
                }
            }

            $('#nextUser').on('click', function() {
                location.reload();
            })
        </script>
    </body>
</html>
