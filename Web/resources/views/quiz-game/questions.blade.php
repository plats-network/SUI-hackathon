@extends('quiz-game.layout_question')
@section('content')
    {{-- WAITING FOR PLAYER --}}
    <div class="waiting-player -container-fluid full-width body-section"
        style="display:block; background-image: url('/events/quiz-game/background.jpg')">
        <div class="overlay">
            <div class="container">
                <div class="col-12">
                    <div class="wrap-quiz-name d-flex justify-content-between">
                        <div class="content">
                            <h2>Join with Plats App</h1>
                                <p>Your Quiz Game:</p>
                                <h1>{{ $event->name }}</h1>
                        </div>
                        <img src="data:image/png;base64, {!! base64_encode($qrCode) !!}" alt="QR Code" style="max-width: 400px;">
                    </div>
                    <div class="div">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="number-players">
                                <h2>0</h2>
                                <i>Players</i>
                            </div>
                            <h2 class="mb-0">Waiting for players</h2>
                            <button class="btn-plats" onclick="startQuizGame()">Start</button>
                        </div>
                    </div>
                    <div class="lists">
                        @foreach ($listJoinedUsers as $user)
                            <span class='nickname'>{{ $user['name'] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- END WAITING FOR PLAYER --}}

    {{-- PREPARE START THE GAME --}}
    <div class="wrap-prepare-start start-quiz body-section"
        style="display: none; background-image: url('/events/quiz-game/background.jpg')">
        <div class="container">
            <div class="wrap-quiz">
                <p class="quiz-name">{{ $event->name }}</p>
            </div>
            <div class="countdown">
                5
            </div>
            <div class="total-question">
                <p>{{ $totalQuiz }} questions</p>
                <h3>Are you ready?</h3>
            </div>
        </div>
    </div>
    {{-- END PREPARE START THE GAME --}}

    {{-- START QUESTION --}}
    <div class="wrap-prepare-start start-question body-section"
        style="display: none; background-image: url('/events/quiz-game/background.jpg')">
        <div class="container">
            <div class="wrap-quiz">
                <p class="quiz-name"></p>
            </div>
            <div class="countdown"></div>
        </div>
    </div>
    {{-- END START QUESTION --}}

    {{-- QUESTION DETAIL --}}
    <div class="wrap-question-detail body-section" style="display: none;">
        <div class="container">
            <h1 class="question-name text-center"></h1>
            <div class="wrap-question-image d-flex justify-between">
                <div class="col-4">
                    <div class="countdown"></div>
                </div>
                <div class="col-4">
                    <img src="{{ url('events/quiz-game/background.png') }}" alt="Image question">
                </div>
                <div class="col-4 right-content">
                    <button class="btn-plats" onclick="showResultAnswer()">Show result</button>
                    {{-- <button class="skip-question">Skip</button>
                    <div class="number-answers">
                        <h3>24</h3>
                        <p>Answers</p>
                    </div> --}}
                </div>
            </div>
            <div class="wrap-answers">
                <div class="wrap-line d-flex">
                    <div class="col-6">
                        <div class="answer-box polygon">
                            <img src="{{ url('events/quiz-game/polygon.png') }}" alt="polygon" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="answer-box ellipse">
                            <img src="{{ url('events/quiz-game/ellipse.png') }}" alt="ellipse" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="wrap-line d-flex">
                    <div class="col-6">
                        <div class="answer-box star">
                            <img src="{{ url('events/quiz-game/star.png') }}" alt="star" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="answer-box rectangle">
                            <img src="{{ url('events/quiz-game/rectangle.png') }}" alt="rectangle" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END QUESTION DETAIL --}}

    {{-- QUESTION RESULT AND STATS --}}
    <div class="wrap-question-result body-section" style="display: none;">
        <div class="container">
            <h1 class="question-name text-center"></h1>
            <div class="wrap-btn-next">
                <button class="btn-plats" onclick="showScoreboard()">Next</button>
            </div>
            <div class="wrap-stats d-flex flex-wrap">
                <div class="stats col-md-6 col-12 d-flex">
                    <div class="item col-3">
                        <div class="free-space"></div>
                        <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct-polygon.svg') }}"
                            alt="">
                        <div class="percentage polygon"></div>
                        <div class="answer-box polygon" data-value="A">
                            <img src="{{ url('events/quiz-game/polygon.png') }}" alt="polygon" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                    <div class="item col-3">
                        <div class="free-space"></div>
                        <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct-ellipse.svg') }}"
                            alt="">
                        <div class="percentage ellipse"></div>
                        <div class="answer-box ellipse" data-value="B">
                            <img src="{{ url('events/quiz-game/ellipse.png') }}" alt="ellipse" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                    <div class="item col-3">
                        <div class="free-space"></div>
                        <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct-star.svg') }}"
                            alt="">
                        <div class="percentage star"></div>
                        <div class="answer-box star" data-value="C">
                            <img src="{{ url('events/quiz-game/star.png') }}" alt="star" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                    <div class="item col-3 pr-0">
                        <div class="free-space"></div>
                        <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct-rectangle.svg') }}"
                            alt="">
                        <div class="percentage rectangle"></div>
                        <div class="answer-box rectangle" data-value="D">
                            <img src="{{ url('events/quiz-game/rectangle.png') }}" alt="rectangle" class="img-fluid">
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="wrap-answers col-md-6 col-12">
                    <div class="col-12">
                        <div class="answer-box polygon d-flex justify-content-between" data-value="A">
                            <div class="d-flex align-items-center">
                                <img src="{{ url('events/quiz-game/polygon.png') }}" alt="polygon" class="img-fluid">
                                <p></p>
                            </div>
                            <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct.svg') }}"
                                alt="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="answer-box ellipse d-flex justify-content-between" data-value="B">
                            <div class="d-flex align-items-center">
                                <img src="{{ url('events/quiz-game/ellipse.png') }}" alt="ellipse" class="img-fluid">
                                <p></p>
                            </div>
                            <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct.svg') }}"
                                alt="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="answer-box star d-flex justify-content-between" data-value="C">
                            <div class="d-flex align-items-center">
                                <img src="{{ url('events/quiz-game/star.png') }}" alt="star" class="img-fluid">
                                <p></p>
                            </div>
                            <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct.svg') }}"
                                alt="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="answer-box rectangle mb-0 d-flex justify-content-between" data-value="D">
                            <div class="d-flex align-items-center">
                                <img src="{{ url('events/quiz-game/rectangle.png') }}" alt="rectangle"
                                    class="img-fluid">
                                <p></p>
                            </div>
                            <img class="icon-correct" src="{{ url('events/quiz-game/icon-correct.svg') }}"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- QUESTION QUESTION RESULT AND STATS --}}

    {{-- SCOREBOARD --}}
    <div class="wrap-scoreboard normal body-section" style="display: none;">
        <div class="scoreboard">
            <div class="container">
                <h1 class="text-center text-white">Scoreboard</h1>
                <div class="d-flex justify-content-end mb-5">
                    <button class="btn-plats" onclick="nextQuestion()">Next</button>
                </div>
                <div class="wrap-content d-flex flex-wrap">
                    <div class="wrap-top-rank mid-rank col-md-6 col-12">
                        <div class="rank-item d-flex items-center">
                            <div class="rank"></div>
                            <div class="wrap-point d-flex">
                                <div class="nickname"></div>
                                <div class="point"></div>
                            </div>
                        </div>
                    </div>
                    <div class="wrap-mid-rank mid-rank col-md-6 col-12">
                        <div class="rank-item d-flex items-center">
                            <div class="rank"></div>
                            <div class="wrap-point d-flex">
                                <div class="nickname"></div>
                                <div class="point"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END SCOREBOARD --}}
    
    {{-- FINAL SCOREBOARD --}}
    <div class="wrap-scoreboard final body-section" style="display: none;">
        <div class="scoreboard">
            <div class="container">
                <h1 class="text-center text-white">Scoreboard</h1>
                <div class="d-flex justify-content-end mb-5">
                    <button class="btn-plats" onclick="quizComplete()">Finish quiz</button>
                </div>
                <div class="wrap-content d-flex flex-wrap">
                    <div class="top-rank col-md-6 col-12 d-flex text-center">
                        <div class="col-4 second-rank">
                            <div class="rank">2</div>
                            <div class="wrap-point" height="23">
                                <div class="nickname">_</div>
                                <div class="point">0</div>
                            </div>
                        </div>
                        <div class="col-4 first-rank">
                            <div class="rank">1</div>
                            <div class="wrap-point">
                                <div class="nickname">_</div>
                                <div class="point">0</div>
                            </div>
                        </div>
                        <div class="col-4 third-rank">
                            <div class="rank">3</div>
                            <div class="wrap-point">
                                <div class="nickname">_</div>
                                <div class="point">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="mid-rank col-md-6 col-12">
                        <div class="rank-item d-flex items-center">
                            <div class="rank"></div>
                            <div class="wrap-point d-flex">
                                <div class="nickname"></div>
                                <div class="point"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END FINAL SCOREBOARD --}}

    {{-- QUIZ COMPLETED --}}
    <div class="quiz-completed body-section"
        style="display: none;background-image: url('/events/quiz-game/background.jpg')">
        <div class="overlay">
            <div class="container">
                <h1 class="quiz-name px-5">{{ $event->name }}</h1>
                <div class="alert">Quiz Completed</div>
                {{-- <div>
                    <button class="btn-plats d-inline-block p-2 px-4" onclick="showScoreboard()">View result</button>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- END QUIZ COMPLETED --}}

    {{-- HIGHEST SCORES --}}
    <div class="highest-scores body-section" style="display: none;">
        <div class="container">
            <h1 class="text-right text-white">HIGHEST SCORES</h1>
            <div class="wrap-btn-next">
                <button class="btn-plats">Next</button>
            </div>
            <div class="top-rank col-md-6 col-12 d-flex text-center mx-auto">
                <div class="col-4 second-rank">
                    <div class="rank">2</div>
                    <div class="wrap-point" height="23">
                        <div class="nickname">Oppa</div>
                        <div class="point">255</div>
                    </div>
                </div>
                <div class="col-4 first-rank">
                    <div class="rank">1</div>
                    <div class="wrap-point">
                        <div class="nickname">Oppa</div>
                        <div class="point">255</div>
                    </div>
                </div>
                <div class="col-4 third-rank">
                    <div class="rank">3</div>
                    <div class="wrap-point">
                        <div class="nickname">Oppa</div>
                        <div class="point">255</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END HIGHEST SCORES --}}

    {{-- SOUND AFFECT --}}
    <div class="wrap-sound-affect" style="display: none">
        <audio id="startGameSound">
            <source src="{{ url('static/sound/quiz-game/start_game.mp3') }}" type="audio/mpeg">
        </audio>
        <audio id="start5SecondsSound">
            <source src="{{ url('static/sound/quiz-game/5_seconds.mp3') }}" type="audio/mpeg">
        </audio>
    </div>
    {{-- END SOUND AFFECT --}}
@endsection
