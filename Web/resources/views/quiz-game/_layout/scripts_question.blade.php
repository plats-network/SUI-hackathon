<script>
    // ELEMENTS
    const WAITING_PLAYER = $('.waiting-player');
    const PREPARE_START_QUIZ = $('.wrap-prepare-start.start-quiz');
    const PREPARE_START_QUESTION = $('.wrap-prepare-start.start-question');
    const QUESTION_DETAIL = $('.wrap-question-detail');
    const QUESTION_RESULT = $('.wrap-question-result');
    const SCOREBOARD = $('.wrap-scoreboard.normal');
    const SCOREBOARD_FINAL = $('.wrap-scoreboard.final');
    const QUIZ_COMPLETED = $('.quiz-completed');
    const SPINNER = $('#loading-overlay');
    const STARTGAMESOUND = $('#startGameSound')[0];

    // STEP SCREEN
    const WAITING_PLAYER_STEP = 1;
    const PREPARE_START_QUIZ_STEP = 2;
    const PREPARE_START_QUESTION_STEP = 3;
    const QUESTION_DETAIL_STEP = 4;
    const QUESTION_RESULT_STEP = 5;
    const SCOREBOARD_STEP = 6;
    const QUIZ_COMPLETED_STEP = 7;

    // Declare global variable
    const EVENT_ID = "{{ $event->id }}";
    const TIME_PREPARE_QUESTION = 3;
    const TIME_PREPARE_START_GAME = 5;
    var app = {
        userId: null,
        questionId: null,
        nickname: '',
        currentQuestion: 0,
        totalQuestion: null,
        answers: [],
        correctAnswer: '',
        countDownSeconds: 0,
        countDownSecondsStartGame: 0,
        countDownSecondsStartQuestion: 0,
        questionName: '',
        questionImage: null,
        timeToAnswer: 0,
        currentStep: 1,
        expirationQuestionTime: null,
        isFinalQuestion: false,
        isFinishQuiz: false,
        currentTime: new Date(),
        timeExpirationStorage: new Date(new Date().getTime() + 60 * 60000), // Save data to localStorage 60 minutes
    }

    // Bind event pusher
    pusher.subscribe("NextQuestion_" + EVENT_ID).bind("NextQuestionEvent", handleNextQuestion);
    pusher.subscribe("UserJoinQuiz_" + EVENT_ID).bind("UserJoinQuizEvent", handleUserJoinQuiz);

    // Get variable from localStorage in case reload
    var storeQuizQuestionVariable = localStorage.getItem('quizQuestionVariable') ? JSON.parse(localStorage.getItem(
        'quizQuestionVariable')) : null;
    if (storeQuizQuestionVariable) {
        let isValidStorage = new Date(storeQuizQuestionVariable.timeExpirationStorage) > new Date(app.currentTime);
        if (!storeQuizQuestionVariable.isFinishQuiz && isValidStorage) {
            app = storeQuizQuestionVariable;
            // Check current screen step
            handleScreenStep(storeQuizQuestionVariable.currentStep);

            // Bind data question
            bindDataQuestion(QUESTION_DETAIL);
        }
    }

    // Catch even reload page
    window.onbeforeunload = function() {
        return "";
    }

    $(document).ready(function() {});

    // Handle start quiz game
    function startQuizGame() {
        if (app.currentQuestion === 0) {
            // Start sound if game ready
            STARTGAMESOUND.play();
        }
        app.countDownSecondsStartGame = TIME_PREPARE_START_GAME;

        // Show select answer screen
        handleScreenStep(PREPARE_START_QUIZ_STEP);

        // Countdown second to get point
        countDownSecondsTimer(PREPARE_START_QUIZ_STEP);

        // Move to prepare question screen
        setTimeout(() => {
            nextQuestion();
        }, TIME_PREPARE_START_GAME * 1000);
    }

    // Handle next question
    function nextQuestion() {

        // Prevent double click next question
        QUESTION_DETAIL.find('.btn-plats').attr('disabled', true);

        if (app.totalQuestion && app.currentQuestion >= app.totalQuestion) {
            app.currentStep = QUIZ_COMPLETED_STEP;
            handleScreenStep(QUIZ_COMPLETED_STEP)

            // Finish sound if game is over
            STARTGAMESOUND.currentTime = 0
            STARTGAMESOUND.pause();

            // Remove localstorage
            localStorage.removeItem('quizQuestionVariable')
            SPINNER.hide();

            return;
        }
        // Set count down start question
        app.countDownSecondsStartQuestion = TIME_PREPARE_QUESTION;
        SPINNER.show();
        $.ajax({
            url: '/quiz-game/next-question',
            method: 'POST',
            data: {
                eventId: EVENT_ID,
                questionNumber: parseInt(app.currentQuestion) + 1,
            },
            success: function(res) {
                // Handle successful submission
                PREPARE_START_QUESTION.find('.quiz-name').text(res[0].name);
                PREPARE_START_QUESTION.find('.countdown').text(TIME_PREPARE_QUESTION);
                // Countdown second to get point
                countDownSecondsTimer(PREPARE_START_QUESTION_STEP);
            },
            error: function(error) {
                // Handle submission error
            },
            complete: function(data) {
                // A function to be called when the request finishes 
                // (after success and error callbacks are executed).
                SPINNER.hide();

                // Move to prepare question
                handleScreenStep(PREPARE_START_QUESTION_STEP)
            }
        });
    }

    // Listen event next question to handle on player screen
    function handleNextQuestion(data) {
        setTimeout(() => {
            var question = data?.data
            // Show select answer screen
            handleScreenStep(QUESTION_DETAIL_STEP);

            // Assigne value for question content
            app.questionName = question.name;
            app.questionImage = question.image;
            app.questionId = question.id;
            app.countDownSeconds = question.timeToAnswer;
            app.currentQuestion = question.number;
            app.answers = question.answers;
            app.totalQuestion = question.totalQuestion;
            app.countDownSecondsStartQuestion = 5;
            app.correctAnswer = question.correctAnswer;
            app.currentStep = QUESTION_DETAIL_STEP;
            app.isFinalQuestion = question.totalQuestion === question.number

            // Bind new data question 
            bindDataQuestion(QUESTION_DETAIL)

            // Countdown second to get point
            countDownSecondsTimer(QUESTION_DETAIL_STEP, question.correctAnswer);

            // Save variable to storage
            localStorage.setItem('quizQuestionVariable', JSON.stringify(app));
        }, TIME_PREPARE_QUESTION * 1000);
    }

    // Bind data question
    function bindDataQuestion(element, isDataStorage = false) {
        if (isDataStorage) {
            element.find('.countdown').text(app.countDownSeconds);
        } else {
            element.find('.countdown').text(app.timeToAnswer);
        }
        element.find('.question-name').text(app.questionName);
        element.find('.wrap-question-image img').attr('src', app.questionImage);
        app.answers.forEach((value, index) => {
            let boxAnswer = element.find('.wrap-answers .answer-box').get(index);
            $(boxAnswer).find('p').text(value.name);
        });
    }

    // Countdown seconds to caculate the point
    function countDownSecondsTimer(step, correctAnswer = '') {
        var self = this;
        // Handle coundown for question detail step
        if (step === QUESTION_DETAIL_STEP) {
            QUESTION_DETAIL.find('#' + correctAnswer).removeClass('animated flash');
            if (app.countDownSeconds > 0) {
                setTimeout(function() {
                    app.countDownSeconds -= 1;
                    self.countDownSecondsTimer(QUESTION_DETAIL_STEP, correctAnswer);
                }, 1000);
                QUESTION_DETAIL.find('.countdown').text(app.countDownSeconds)
            } else {
                // Show correct answer
                QUESTION_DETAIL.find('#' + correctAnswer).addClass('animated flash');

                // Enabled button next question
                QUESTION_DETAIL.find('.btn-plats').attr('disabled', false);

                // Reset countdown timer to zero 
                QUESTION_DETAIL.find('.countdown').text(0)
            }
        } else if (step === PREPARE_START_QUIZ_STEP) {
            if (app.countDownSecondsStartGame > 0) {
                setTimeout(function() {
                    app.countDownSecondsStartGame -= 1;
                    self.countDownSecondsTimer(PREPARE_START_QUIZ_STEP);
                }, 1000);
            }
            PREPARE_START_QUIZ.find('.countdown').text(app.countDownSecondsStartGame)
        } else if (step === PREPARE_START_QUESTION_STEP) {
            if (app.countDownSecondsStartQuestion > 0) {
                setTimeout(function() {
                    app.countDownSecondsStartQuestion -= 1;
                    self.countDownSecondsTimer(PREPARE_START_QUESTION_STEP);
                }, 1000);
            }
            PREPARE_START_QUESTION.find('.countdown').text(app.countDownSecondsStartQuestion)
        }
    }

    // Change the step screen
    function handleScreenStep(step) {
        switch (step) {
            case WAITING_PLAYER_STEP:
                // Show welcome screen
                WAITING_PLAYER.show();
                break;

            case PREPARE_START_QUIZ_STEP:
                // Hide previous screen
                WAITING_PLAYER.hide();

                // Show select answer screen
                PREPARE_START_QUIZ.show();
                break;

            case PREPARE_START_QUESTION_STEP:
                // Hide previous screen
                WAITING_PLAYER.hide();
                PREPARE_START_QUIZ.hide();

                // Show select answer screen
                PREPARE_START_QUESTION.show("fast");
                break;

            case QUESTION_DETAIL_STEP:
                // Hide other screen
                WAITING_PLAYER.hide();
                PREPARE_START_QUIZ.hide();
                PREPARE_START_QUESTION.hide();

                // Show select answer screen
                QUESTION_DETAIL.show("slow");
                break;

            case QUESTION_RESULT_STEP:
                // Hide other screen
                WAITING_PLAYER.slideUp();
                PREPARE_START_QUIZ.slideUp();
                PREPARE_START_QUESTION.slideUp();
                QUESTION_DETAIL.slideUp();

                // Show select answer screen
                QUESTION_RESULT.slideDown();
                break;

            case SCOREBOARD_STEP:
                // Hide other screen
                WAITING_PLAYER.hide();
                PREPARE_START_QUIZ.hide();
                PREPARE_START_QUESTION.hide();
                QUESTION_DETAIL.hide();
                QUESTION_RESULT.hide();

                // Show select answer screen
                if (app.isFinalQuestion) {
                    SCOREBOARD.hide();
                    SCOREBOARD_FINAL.show();
                } else {
                    SCOREBOARD_FINAL.hide();
                    SCOREBOARD.show();
                }
                break;

            case QUIZ_COMPLETED_STEP:
                // Hide other screen
                WAITING_PLAYER.hide();
                QUESTION_DETAIL.hide();
                SCOREBOARD.hide();
                SCOREBOARD_FINAL.hide();

                // Show quiz complete screen
                QUIZ_COMPLETED.show();
                break;

            default:
                break;
        }
    }

    // Handle user join quiz
    function handleUserJoinQuiz(data) {
        var usersJoined = data?.data;
        var nicknameElement = $("<span class='nickname'></span>")
        WAITING_PLAYER.find('.number-players h2').text(usersJoined.length);
        var listsElement = '';
        usersJoined.forEach(element => {
            let nicknameElement = "<span class='nickname'>" + element.name + "</span>";
            listsElement += nicknameElement;
        });
        WAITING_PLAYER.find('.lists').empty();
        WAITING_PLAYER.find('.lists').append(listsElement);
    }
    // Handle show result answers
    function showResultAnswer() {
        // Bind data question
        bindDataQuestion(QUESTION_RESULT);
        let summaryAnswer = '';
        let totalAnswered = 0;
        SPINNER.show();
        $.ajax({
            url: '/quiz-game/summary-results/' + EVENT_ID,
            data: {
                'quiz_id': app.questionId,
                'isLastQuestion': app.isFinalQuestion
            },
            method: 'GET',
            success: function(res) {
                // Handle successful submission
                summaryAnswer = res.summaryAnswer;
                totalAnswered = res.totalAnswered;

                // Show correct answers
                summaryAnswer.forEach((value, index) => {
                    let boxStats = QUESTION_RESULT.find('.wrap-stats .stats .item').get(index);
                    let boxAnswers = QUESTION_RESULT.find('.wrap-answers .answer-box').get(index);
                    $(boxStats).find('.answer-box p').text(value.quiz_results_count);

                    // Caculate height (110px is the height of except class percentage)
                    let defaultHeight = "(100% - 110px)";
                    let percentAnswer = totalAnswered ? parseInt(value.quiz_results_count) /
                        totalAnswered : 0;
                    let percentage = percentAnswer + '*' + defaultHeight;
                    $(boxStats).find('.percentage').css('height', 'calc(' + percentage + ')');
                    $(boxStats).find('.free-space').css('height', 'calc(' + defaultHeight + ' - ' +
                        percentage + ')');

                    if (value.id === app.correctAnswer) {
                        $(boxStats).find('.icon-correct').css('visibility', 'visible');
                        $(boxAnswers).find('.icon-correct').css('visibility', 'visible');
                    } else {
                        $(boxStats).find('.icon-correct').css('visibility', 'hidden');
                        $(boxAnswers).find('.icon-correct').css('visibility', 'hidden');
                    }
                });
            },
            error: function(error) {
                // Handle submission error
            },
            complete: function(data) {
                // A function to be called when the request finishes 
                // (after success and error callbacks are executed).
                SPINNER.hide();
                // Move to prepare question
                handleScreenStep(QUESTION_RESULT_STEP);
            }
        });
    }

    // Show scoreboard
    function showScoreboard() {
        SPINNER.show();
        $.ajax({
            url: '/quiz-game/scoreboard/' + EVENT_ID,
            data: {
                'quiz_id': app.questionId,
                'isLastQuestion': app.isFinalQuestion
            },
            method: 'GET',
            success: function(res) {
                // Handle successful submission
                let scoreboard = res.scoreboard;
                let topScoreboard = res.topScoreboard;
                // Scoreboard
                if (!app.isFinalQuestion) {
                    let topRank = '';
                    let midRank = '';
                    for (let rank = 1; rank <= 10; rank++) {
                        let element = scoreboard[rank - 1] ?? null;
                        let nickname = element ? element.user.name : '_';
                        let point = element ? element.point : 0;
                        rankItem =
                            "<div class='rank-item d-flex items-center'><div class='rank' style='background-color:" +
                            getColorRank(rank) + "'>" + rank +
                            "</div><div class='wrap-point d-flex'>" +
                            "<div class='nickname'>" + nickname + "</div><div class='point'>" +
                            point +
                            "</div></div></div>";
                        if (rank < 6) {
                            topRank += rankItem;
                        } else {
                            midRank += rankItem;
                        }
                    }
                    SCOREBOARD.find('.wrap-top-rank').empty();
                    SCOREBOARD.find('.wrap-top-rank').append(topRank);
                    SCOREBOARD.find('.wrap-mid-rank').empty();
                    SCOREBOARD.find('.wrap-mid-rank').append(midRank);
                } else {
                    // Show other scoreboard if final questions
                    showFinalScoreboard(topScoreboard);

                    // Allow user reload page doesnt need to confirm
                    window.onbeforeunload = null;
                }
            },
            error: function(error) {
                // Handle submission error
            },
            complete: function(data) {
                // A function to be called when the request finishes 
                // (after success and error callbacks are executed).
                SPINNER.hide();
                // Move to prepare question
                handleScreenStep(SCOREBOARD_STEP)
            }
        });
    };
    // Show scoreboard
    function quizComplete() {
        handleScreenStep(QUIZ_COMPLETED_STEP)
    };

    // Show final scoreboard
    function showFinalScoreboard(data) {
        app.isFinishQuiz = true;

        // Save variable to storage
        localStorage.setItem('quizQuestionVariable', JSON.stringify(app));

        // First rank
        let firstRank = data[0] ?? null;
        if (firstRank) {
            SCOREBOARD_FINAL.find('.first-rank .nickname').text(firstRank.user.name);
            SCOREBOARD_FINAL.find('.first-rank .point').text(firstRank.point);
        }

        // Second rank
        let secondRank = data[1] ?? null;
        if (secondRank) {
            SCOREBOARD_FINAL.find('.second-rank .nickname').text(secondRank.user.name);
            SCOREBOARD_FINAL.find('.second-rank .point').text(secondRank.point);
        }

        // Third rank
        let thirdRank = data[2] ?? null;
        if (thirdRank) {
            SCOREBOARD_FINAL.find('.third-rank .nickname').text(thirdRank.user.name);
            SCOREBOARD_FINAL.find('.third-rank .point').text(thirdRank.point);
        }

        // Mid rank
        var rankItem = SCOREBOARD_FINAL.find('.mid-rank .rank-item').get(0);
        var midRank = '';
        for (let rank = 4; rank <= 10; rank++) {
            let element = data[rank - 1] ?? null;
            let nickname = element ? element.user.name : '_';
            let point = element ? element.point : 0;
            midRank +=
                "<div class='rank-item d-flex items-center'><div class='rank' style='background-color:" +
                getColorRank(rank) + "'>" + rank +
                "</div><div class='wrap-point d-flex'>" +
                "<div class='nickname'>" + nickname + "</div><div class='point'>" + point +
                "</div></div></div>";
        }
        SCOREBOARD_FINAL.find('.mid-rank').empty();
        SCOREBOARD_FINAL.find('.mid-rank').append(midRank);
    };

    // Get color scoreboard
    function getColorRank(rank) {
        let color = '';
        switch (rank) {
            case 1:
                color = '#F4DB00';
                break;
            case 2:
                color = '#61F370';
                break;
            case 3:
                color = '#F361C1';
                break;
            case 4:
                color = '#61F3D0';
                break;
            case 5:
                color = '#F061F3';
                break;
            case 6:
                color = '#B98BF4';
                break;
            case 7:
                color = '#FEB4B4';
                break;
            case 8:
                color = '#5D4FFF';
                break;
            case 9:
                color = '#F48707';
                break;
            case 10:
                color = '#FFFAFA';
                break;

            default:
                break;
        }

        return color;
    }
</script>
