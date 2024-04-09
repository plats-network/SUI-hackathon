<script>
    // ELEMENTS
    const HEADER = $('header');
    const WELCOME_USER = $('.welcome-users');
    const PREPARE_ANSWER = $('.prepare-answer');
    const SELECT_ANSWER = $('.select-answers');
    const CORRECT_ANSWER = $('.correct-answer');
    const INCORRECT_ANSWER = $('.incorrect-answer');
    const QUIZ_COMPLETED = $('.quiz-completed');
    const FINAL_RANK = $('.final-rank');
    const FOOTER = $('footer');
    const INCORRECT_SOUND = $('#incorrectSound')[0];
    const CORRECT_SOUND = $('#correctSound')[0];

    // STEP SCREEN
    const WELCOME_STEP = 1;
    const PREPARE_ANSWER_STEP = 2;
    const SELECT_ANSWER_STEP = 3;
    const QUIZ_COMPLETED_STEP = 4;
    const FINAL_RANK_STEP = 5;

    // Declare global variable
    const EVENT_ID = "{{ $eventId }}";
    const USER_ID = "{{ $userId }}";
    const TIME_PREPARE_ANSWER = 3;
    var app = {
        userId: null,
        questionId: null,
        nickname: '',
        currentQuestion: 0,
        totalQuestion: 0,
        answers: [],
        selectedAnswer: null,
        correctAnswer: '',
        countDownMilliseconds: 0,
        currentQuestionPoints: 0,
        isAnswered: false,
        totalPoint: 0,
        isFinishQuiz: false,
        timeToAnswer: null,
        replyExpirationTime: null,
        currentStep: 1,
        currentTime: new Date(),
        timeExpirationStorage: new Date(new Date().getTime() + 60 * 60000), // Save data to localStorage 60 minutes
    }

    // Bind event pusher
    pusher.subscribe("NextQuestion_" + EVENT_ID).bind("NextQuestionEvent", handleNextQuestion);
    pusher.subscribe("SummaryResultQuizGame_" + EVENT_ID).bind("SummaryResultQuizGameEvent", handleSummaryResult);

    // Get variable from localStorage in case reload
    var storeQuizAnswerVariable = localStorage.getItem('quizAnswerVariable') ? JSON.parse(localStorage.getItem(
        'quizAnswerVariable')) : null;
    if (storeQuizAnswerVariable) {
        let isValidStorage = new Date(storeQuizAnswerVariable.timeExpirationStorage) > new Date(app.currentTime);
        if (!storeQuizAnswerVariable.isFinishQuiz && isValidStorage) {
            app = storeQuizAnswerVariable;
            // Check current screen step
            checkScreenStep(storeQuizAnswerVariable.currentStep);

            // Set label for header
            HEADER.find('h2').text(storeQuizAnswerVariable.currentQuestion + ' of ' + storeQuizAnswerVariable
                .totalQuestion);

            // Set point
            FOOTER.find('.point').text(storeQuizAnswerVariable.totalPoint);

        }
    }

    // Catch even reload page
    $(window).on('beforeunload', function(event) {
        return "";
    });

    $(document).ready(function() {
        // User select answer
        $('.answers .answer-box').click(function() {
            var self = this;
            var selectedAnswer = $(this).data('id');
            var timeNow = new Date();
            // Check end of reply time or answered
            if (app.isAnswered || !app.countDownMilliseconds || timeNow > new Date(app.replyExpirationTime)) {
                showFlashMessage('Answered or time is over!')
                return;
            }
            app.selectedAnswer = selectedAnswer;
            app.isAnswered = true;
            if (app.selectedAnswer !== app.correctAnswer) {
                app.currentQuestionPoints = 0;
                SELECT_ANSWER.hide();
                INCORRECT_ANSWER.find('.point').text('+ ' + app.currentQuestionPoints);
                FOOTER.find('.point').text(app.totalPoint);
                INCORRECT_ANSWER.show();

                // Turn on incorrect sound
                INCORRECT_SOUND.play();
            } else {
                app.currentQuestionPoints = app.countDownMilliseconds;
                app.totalPoint += parseInt(app.currentQuestionPoints);
                SELECT_ANSWER.hide();
                CORRECT_ANSWER.find('.point').text('+ ' + app.currentQuestionPoints);
                FOOTER.find('.point').text(app.totalPoint);
                CORRECT_ANSWER.show();

                // Turn on correct sound
                CORRECT_SOUND.play();
            }
            // Send total point
            sendTotalPoint();

            // Reset countDownMilliseconds to stop function countDownMillisecondsTimer
            app.countDownMilliseconds = 0;

            // Save variable to storage
            localStorage.setItem('quizAnswerVariable', JSON.stringify(app));
        });
    });


    // Listen event next question to handle on player screen
    function handleNextQuestion(data) {
        // Assign data from pusher
        var question = data?.data

        // Show prepare answers screen
        checkScreenStep(PREPARE_ANSWER_STEP);
        PREPARE_ANSWER.find('.question-name').text(question.name)
        SELECT_ANSWER.find('.question-name').text(question.name)
        setTimeout(() => {

            // Show select answer screen
            checkScreenStep(SELECT_ANSWER_STEP);

            // Caculate time to answer to milisecond to calculate the point
            app.countDownMilliseconds = parseInt(question.timeToAnswer) * 100;
            var timeNow = new Date();
            // Reset status answer
            app.isAnswered = false;
            app.correctAnswer = question.correctAnswer;
            app.answers = question.answers;
            app.questionId = question.id;
            app.currentQuestion = question.number;
            app.totalQuestion = question.totalQuestion;
            app.currentStep = SELECT_ANSWER_STEP;
            app.replyExpirationTime = timeNow.setSeconds(timeNow.getSeconds() + parseInt(question.timeToAnswer));
            app.totalPoint = question.number === 1 ? 0 : app.totalPoint
            // Set data
            HEADER.find('h2').text(app.currentQuestion + ' of ' + app.totalQuestion);
            FOOTER.find('.point').text(app.totalPoint);
            app.answers.forEach((value, index) => {
                let boxAnswer = SELECT_ANSWER.find('.answer-box').get(index);
                $(boxAnswer).data("id", value.id);;
            });

            // Countdown milisecond to get point
            countDownMillisecondsTimer();

            // If current question is the last question set timeout to show screen quiz completed
            if (question.number >= question.totalQuestion) {
                app.currentStep = QUIZ_COMPLETED_STEP;

                // Send point to server
                setTimeout(() => {
                    sendTotalPoint();
                }, parseInt(question.timeToAnswer) * 1000);
            }

            // Save variable to storage
            localStorage.setItem('quizAnswerVariable', JSON.stringify(app));

        }, TIME_PREPARE_ANSWER * 1000);
    }

    // Count down milisecond to caculate the point
    function countDownMillisecondsTimer() {
        var self = this;
        if (app.countDownMilliseconds > 0) {
            setTimeout(function() {
                app.countDownMilliseconds -= 1;
                self.countDownMillisecondsTimer();
            }, 10);
        }
    }

    // Change the step screen
    function checkScreenStep(step) {
        switch (step) {

            case WELCOME_STEP:
                // Show welcome screen
                WELCOME_USER.show();
                break;
            case PREPARE_ANSWER_STEP:
                // Hide other screen
                WELCOME_USER.hide("slow");
                CORRECT_ANSWER.hide("slow");
                INCORRECT_ANSWER.hide("slow");
                SELECT_ANSWER.hide("slow");

                // Show welcome screen
                PREPARE_ANSWER.show("slow");
                break;

            case SELECT_ANSWER_STEP:
                // Hide other screen
                WELCOME_USER.hide("slow");
                PREPARE_ANSWER.hide("slow");
                QUIZ_COMPLETED.hide();
                CORRECT_ANSWER.hide();
                INCORRECT_ANSWER.hide();

                // Show select answer screen
                SELECT_ANSWER.show("slow");
                break;
            case FINAL_RANK_STEP:
                // Hide other screen
                WELCOME_USER.hide();
                CORRECT_ANSWER.hide();
                INCORRECT_ANSWER.hide();
                SELECT_ANSWER.hide();
                PREPARE_ANSWER.hide();
                HEADER.hide();
                FOOTER.hide();
                // Show quiz complete screen
                FINAL_RANK.show();
                break;

            case QUIZ_COMPLETED_STEP:
                // Hide other screen
                WELCOME_USER.hide();
                CORRECT_ANSWER.hide();
                INCORRECT_ANSWER.hide();
                PREPARE_ANSWER.hide("slow");
                SELECT_ANSWER.hide("slow");
                FINAL_RANK.hide("slow");
                HEADER.show("slow");
                FOOTER.show("slow");
                // Show quiz complete screen
                QUIZ_COMPLETED.show();
                break;

            default:
                break;
        }
    }

    // Show flash message in 3 seconds and hide it
    function showFlashMessage(message = '') {
        var flashMessage = $('#flashMessage');
        flashMessage.show();
        flashMessage.text(message)
        setTimeout(function() {
            flashMessage.hide();
        }, 3000); // 3 seconds
    }

    // Send total point to server
    function sendTotalPoint() {
        // Send the selected answer to the server
        var data = {
            eventId: EVENT_ID,
            answerId: app.selectedAnswer,
            totalPoint: app.totalPoint
        };
        $.ajax({
            url: '/quiz-game/send-total-score',
            method: 'POST',
            data: data,
            success: function() {
                // Handle successful submission
            },
            error: function(error) {
                // Handle submission error
            }
        });
    }
    // Handle show rank on player screen
    function handleSummaryResult(data) {

        // Allow user reload page doesnt need to confirm
        window.onbeforeunload = null;

        app.isFinishQuiz = true;
        localStorage.setItem('quizQuestionVariable', JSON.stringify(app));

        var scoreboard = data?.data;
        $.each(scoreboard, function(index, element) {
            if (element.user_id === USER_ID) {
                let rank = index + 1;
                FINAL_RANK.find('.point').text(element.point);
                if (rank <= 10) {
                    FINAL_RANK.find('.rank').text(rank);
                    FINAL_RANK.find('.top-high-rank').show();
                    FINAL_RANK.find('.other-rank').hide();
                } else {
                    FINAL_RANK.find('.other-rank').show();
                    FINAL_RANK.find('.top-high-rank').hide();
                    FINAL_RANK.find('.rank').text(rank + 'th');
                    FINAL_RANK.css('background', '#3B3494');
                }
                return false;
            }
        });
        checkScreenStep(FINAL_RANK_STEP);

        // Move to the quiz complete screen and remove localStorage after delay 5 seconds
        setTimeout(() => {
            checkScreenStep(QUIZ_COMPLETED_STEP);

            // Remove localstorage
            localStorage.removeItem('quizAnswerVariable')
        }, 10000);
    }
</script>
