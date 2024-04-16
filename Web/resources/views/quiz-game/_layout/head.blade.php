<!-- Font Tags Start -->
<link rel="preconnect" href="https://fonts.gstatic.com" />
<!-- Font Tags End -->
<!-- Vendor Styles Start -->
<link rel="stylesheet" href="{{ url('static/css/admin/vendor.css') }}" />
@stack('css')
<!-- Vendor Styles End -->
<style>
    @font-face {
        font-family: Quicksand-Regular;
        src: url("{{ url('static/fonts/quicksand/Quicksand-Regular.ttf') }}");
    }
    @font-face {
        font-family: Quicksand-Bold;
        src: url("{{ url('static/fonts/quicksand/Quicksand-Bold.ttf') }}");
    }

    body {
        font-family: Quicksand-Bold, sans-serif
    }

    .animated {
        background-image: url(/css/images/logo.png);
        background-repeat: no-repeat;
        background-position: left top;
        padding-top: 95px;
        margin-bottom: 60px;
        -webkit-animation-duration: 2s;
        animation-duration: 2s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }

    @keyframes flash {

        0%,
        20%,
        40%,
        60%,
        80%,
        100% {
            opacity: 1;
        }

        10%,
        30%,
        50%,
        70%,
        90% {
            opacity: 0.5;
        }
    }

    .flash {
        animation-name: flash;
    }

    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Màu sắc overlay */
        z-index: 9999;
        /* Đảm bảo overlay nằm trên tất cả các phần tử khác */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .spinner-border {
        color: #fff;
        /* Màu sắc spinner */
    }

    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    #flashMessage {
        display: none;
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
    }

    /* ANSWERS */
    .answers {
        color: #FFFFFF;
    }

    .answers header {
        background-color: #3E4245;
        padding: 20px;
        text-align: center;
        color: #FFFFFF;
        font-size: 20px;
    }

    .answers footer {
        background-color: #3E4245;
        padding: 20px;
        color: #000000;
    }

    .answers .body-section {
        display: flex;
        align-items: center;
        padding: 50px;
        text-align: center;
        color: #000000;
        height: calc(100vh - 160px);
        overflow: hidden;
    }

    .answers header {
        height: 80px;
    }

    .answers footer {
        height: 80px;
    }

    .answers .welcome-users {
        background: #E9CD3A;
    }

    .answers .wrap-point {
        display: inline-block;
        background-color: #FFFFFF;
        color: #000000;
        padding: 10px;
        border-radius: 8px
    }

    .answers .correct-answer {
        background: #43B65D;
    }

    .answers .incorrect-answer {
        background: #F0410A;
    }

    .answers .quiz-completed {
        background: #409EFF;
    }

    .answers .correct-answer .point {
        background: #209F3B;
    }

    .answers .incorrect-answer .point {
        background: #B53912;
    }

    .answers .status-answer .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .answers .status-answer .point {
        border-radius: 5px;
        padding: 15px 120px;
    }

    .answers .status-answer h3 {
        font-style: normal;
        font-weight: 400;
        font-size: 30px;
        text-align: center;
        letter-spacing: 0.01em;
        color: #FFFFFF;
    }

    .answers .status-answer img {
        margin: 50px 0;
    }

    .answers .status-answer .point {
        font-style: normal;
        font-weight: 400;
        font-size: 20px;
        text-align: center;
        color: #FFFFFF;
    }

    .answers .status-answer .point {
        font-style: normal;
        font-weight: 400;
        font-size: 20px;
        text-align: center;
        color: #FFFFFF;
    }

    .answers .quiz-completed h4 {
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 22px;
        text-align: center;
        color: #F8F8F8;
    }

    .answers .quiz-completed img {
        margin: 0;
        margin-bottom: 30px;
    }

    .answers .quiz-completed h2 {
        font-style: normal;
        font-weight: 400;
        font-size: 35px;
        letter-spacing: 0.01em;
        color: #000000;
        margin-top: 80px;

    }

    .select-answers .answer-box {
        padding: 20px;
        margin: 20px;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .select-answers .question-name {
        color: #212529!important;
        margin-bottom: 20px;
    }

    .polygon {
        background: #35A640;
    }

    .ellipse {
        background: #CD1D1D;
    }

    .star {
        background: #D9BB1F;
    }

    .rectangle {
        background: #0F3BD6;
    }

    .btn-plats {
        height: 60px;
        background: #409EFF;
        border-radius: 5px;
        font-weight: 400;
        font-size: 30px;
        letter-spacing: 0.01em;
        color: #FFFFFF;
        border: none;
        padding: 0 50px;
    }

    /* QUESTIONS */

    .questions {
        height: calc(100vh - 100px);
        overflow: hidden;
        background-repeat: no-repeat;
        background-size: cover;
        overflow: hidden;
    }

    .questions .body-section {
        height: 100%;
        background-size: cover;
    }

    .questions header {
        max-height: 100px;
    }

    /* END QUESTIONS */

    /* WAITING PLAYERS */
    .waiting-player {}

    .waiting-player .overlay {
        padding-top: 150px;
    }

    .overlay {
        background: rgba(81, 73, 73, 0.74);
        color: #FFFFFF;
        background-repeat: no-repeat;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
    }

    .waiting-player .wrap-quiz-name {
        background: rgba(15, 14, 14, 0.5);
        border-radius: 5px;
        padding: 20px 70px;
        margin-bottom: 35px;
        color: #FFFFFF;
    }

    .waiting-player .wrap-quiz-name img {
        border-radius: 5px;
    }

    .waiting-player .wrap-quiz-name h1 {
        color: #409EFF;
        font-style: normal;
        font-weight: 400;
        font-size: 60px;
        line-height: 127.67%;
    }

    .waiting-player .lists {
        margin-top: 50px;
        height: calc(100vh - 500px);
    }

    .waiting-player .lists {
        overflow: auto;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #888888 #dddddd;
        /* Other styles as needed */
    }

    .waiting-player .lists::-webkit-scrollbar {
        width: 2px;
        border-radius: 2px;
    }

    .waiting-player .lists::-webkit-scrollbar-thumb {
        background-color: #888888;
        border-radius: 2px;
    }

    .waiting-player .lists::-webkit-scrollbar-thumb:hover {
        background-color: #555555;
    }

    .waiting-player .lists::-webkit-scrollbar-track {
        background-color: #dddddd;
        border-radius: 2px;
    }


    .waiting-player .lists .nickname {
        margin-right: 24px;
        margin-bottom: 15px;
        font-size: 20px;
    }

    /* END WAITING PLAYERS */


    /*  END WAITING FOR PLAYER */
    .wrap-prepare-start .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .wrap-prepare-start .wrap-quiz {
        text-align: center;
        margin-top: 65px;
    }

    .wrap-prepare-start .quiz-name {
        background: rgba(28, 32, 29, 0.71);
        text-align: center;
        color: #FFFFFF;
        font-weight: 400;
        font-size: 50px;
        line-height: 62px;
        padding: 15px 50px;
        border-radius: 5px;
        display: inline-block;
        width: auto;
    }

    .wrap-prepare-start .countdown {
        width: 155px;
        margin: 105px 0;
        background: #F5F5F5;
        border-radius: 5px;
        color: #000000;
        font-weight: 400;
        font-size: 80px;
        text-align: center;
    }

    .wrap-prepare-start .total-question {
        width: 420px;
        background: rgba(36, 28, 28, 0.62);
        border-radius: 5px;
        padding: 30px 0;
    }

    .wrap-prepare-start .total-question p {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-size: 30px;
        line-height: 35px;
        text-align: center;
        color: #FFFFFF;
    }

    .wrap-prepare-start .total-question h3 {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 500;
        font-size: 40px;
        line-height: 47px;
        text-align: center;
        color: #FFFFFF;
    }

    /*  PREPARE START THE GAME */


    /* QUESTION DETAIL */
    .wrap-question-detail .wrap-answers .wrap-line .col-6:first-child {}

    .wrap-question-detail .question-name {
        margin: 30px 0;
    }

    .wrap-question-detail .wrap-question-image .countdown {
        width: 106px;
        height: 106px;
        background: #409EFF;
        border-radius: 50%;
        text-align: center;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-style: normal;
        font-weight: 400;
        font-size: 40px;
    }

    .wrap-question-detail .wrap-answers {
        margin-top: 50px;
    }

    .questions .wrap-answers .answer-box {
        display: flex;
        align-items: center;
        margin: 10px;
        padding: 20px;
        color: #FFFFFF;
        height: 130px;
        overflow: hidden;
        border-radius: 4px;
    }

    .questions .wrap-answers .answer-box p {
        margin-bottom: 0;
        font-family: 'Roboto', sans-serif;
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 35px;
    }

    .questions .wrap-answers .answer-box p {
        margin-bottom: 0;
        margin-left: 20px;
        font-family: 'Roboto', sans-serif;
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 35px;
    }

    .questions .wrap-question-image img {
        width: 100%;
        border-radius: 4px;
        object-fit: contain;
    }

    .wrap-question-detail .wrap-question-image .right-content {
        text-align: right;
    }

    .wrap-question-detail .wrap-question-image .number-answers {
        margin-top: 100px;
    }

    .wrap-question-detail .wrap-question-image .number-answers h3 {
        font-size: 40px;
    }

    .wrap-question-detail .wrap-question-image .number-answers p {
        font-size: 30px;
    }

    .wrap-question-detail .wrap-question-image .skip-question {
        width: 89px;
        background: #ED6C43;
        border-radius: 5px;
        border: none;
        font-weight: 400;
        font-size: 20px;
        letter-spacing: 0.01em;
        color: #FFFFFF;
    }

    /* END QUESTION DETAIL */

    /*  QUESTION RESULT AND STATS */
    .wrap-question-result .question-name {
        margin: 30px 0;
    }

    .wrap-question-result .wrap-btn-next {
        text-align: right;
        margin-bottom: 30px;
    }

    .wrap-question-result .wrap-stats .col-md-6:first-child {
        border-right: 1px solid #409EFF;
        padding-right: 50px;
    }

    .wrap-question-result .wrap-stats .col-md-6:last-child {
        padding-left: 50px;
    }

    .stats .col-3 {
        padding-right: 10px;
        text-align: center
    }

    .stats .answer-box {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        margin-bottom: 0;
        height: 40px;
        line-height: 40px;
    }

    .stats .answer-box p {
        margin-bottom: 0;
    }

    .stats .answer-box img {
        width: 20px;
        margin-right: 8px;
    }

    .stats .percentage {
        display: flex;
        flex-direction: column;
        height: 100%;
        margin-bottom: 10px;
    }

    .stats .answer-box {
        margin-top: auto;
    }

    .stats .col-3:last-child {
        padding-right: 0;
    }

    .stats .icon-correct {
        margin-bottom: 13px;
    }

    /*  END QUESTION RESULT AND STATS */


    /* SCOREBOARD */
    .wrap-scoreboard {
        background: #7138A8;
        height: calc(100vh - 100px);
    }

    .wrap-scoreboard .scoreboard {
        background-image: url('/events/quiz-game/highest-score.png');
        background-repeat: no-repeat;
        text-align: center;
        background-size: contain;
        background-position: top;
    }

    .wrap-scoreboard h1 {
        padding: 25px 0;
    }

    .wrap-scoreboard .btn-plats {
        text-decoration: none;
        line-height: 60px;
    }

    .wrap-scoreboard .btn-plats:hover {
        color: #FFFFFF;
        opacity: 0.6;
    }

    .top-rank .col-4 {
        text-align: center;
        padding-right: 10px;
        display: flex;
        flex-direction: column;
        justify-content: end;
    }

    .top-rank .rank {
        width: 80px;
        height: 80px;
        background: #409EFF;
        text-align: center;
        align-items: center;
        color: white;
        font-size: 50px;
        border-radius: 50%;
        margin: 0 auto;
        margin-bottom: 10px;
    }

    .first-rank .wrap-point {
        background: #E2CB00;
        height: 80%;
    }

    .second-rank .wrap-point {
        background: #61F370;
        height: 65%;
    }

    .third-rank .wrap-point {
        background: #F361C1;
        height: 50%;
    }

    .top-rank .wrap-point {
        padding: 20px 10px;
    }

    .top-rank .wrap-point .nickname {
        font-style: normal;
        font-weight: 400;
        font-size: 30px;
        line-height: 37px;
        text-align: center;
        color: #000000;
    }

    .top-rank .wrap-point .point {
        height: 88%;
        font-style: normal;
        font-weight: 400;
        font-size: 55px;
        line-height: 68px;
        text-align: center;
        color: #FFFFFF;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .wrap-scoreboard .mid-rank {
        align-items: center;
    }

    .wrap-scoreboard .mid-rank .wrap-point .point {
        align-items: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
    }

    .wrap-scoreboard .mid-rank .rank-item {
        margin-bottom: 20px;
        height: 60px;
    }

    .wrap-scoreboard .mid-rank .rank-item:last-child {
        margin-bottom: 0px;
    }

    .wrap-scoreboard .mid-rank .rank {
        min-width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        align-items: center;
        display: flex;
        justify-content: center;
        margin-right: 20px;
    }

    .wrap-scoreboard .mid-rank .wrap-point {
        background: white;
        width: 100%;
        border-radius: 2px;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
    }

    .wrap-scoreboard .top-rank {
        padding-right: 50px;
    }

    .wrap-scoreboard .mid-rank {
        padding-left: 50px;
    }

    /* END SCOREBOARD */

    /* QUIZ COMPLETED */
    .quiz-completed {
        text-align: center
    }

    .quiz-completed .quiz-name {
        background: rgba(28, 32, 29, 0.71);
        display: inline-block;
        margin-top: 60px;
        font-style: normal;
        font-weight: 400;
        font-size: 50px;
        line-height: 62px;
        color: #F5F5F5;
        padding: 15px 30px;
    }

    .quiz-completed .alert {
        display: inline-block;
        background: #F5F5F5;
        border-radius: 5px;
        font-style: normal;
        font-weight: 400;
        font-size: 80px;
        line-height: 99px;
        text-align: center;
        color: #000000;
        margin: 100px 0;
        padding: 20px 150px;
    }

    /* END QUIZ COMPLETED */

    /* QUIZ COMPLETED FINAL RANK */

    .answers .final-rank h4 {
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 22px;
        text-align: center;
        color: #F8F8F8;
    }

    .answers .final-rank img {
        margin: 0;
        margin-bottom: 30px;
    }

    .final-rank {
        text-align: center;
        background: #5299ED;
    }

    .final-rank {
        text-align: center;
        background: #5299ED;
    }

    .final-rank .congrat {
        background-repeat: no-repeat;
        text-align: center;
        background-position: center;
        height: 200px;
        background-size: contain;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .final-rank .congrat {
        font-style: normal;
        font-weight: 400;
        font-size: 50px;
        line-height: 62px;
        text-align: center;
        color: #000000;
    }

    .final-rank .wrap-point-final {
        display: flex;
        justify-content: center;
        color: white;
        font-weight: 400;
        font-size: 25px;
        line-height: 31px;
    }

    .final-rank .player-name {
        font-weight: 400;
        font-size: 20px;
        line-height: 25px;
        margin-top: 30px;
    }

    .answers .final-rank {
        height: 100vh;
    }

    .answers .final-rank .other-rank .good-luck {
        color: #00FFFF;
        font-weight: 400;
        font-size: 25px;
        line-height: 31px;
        text-align: center;
        margin-bottom: 20px;
    }

    .answers .final-rank .other-rank .rank {
        color: #00FFFF;
    }

    .answers .final-rank .other-rank .wrap-point-final {
        font-weight: 400;
        font-size: 12px;
        line-height: 15px;
        text-align: center;
        color: #FFFFFF;
        margin-top: 40px;
    }

    /* END QUIZ COMPLETED FINAL RANK*/

    /* HIGHEST SCORES */
    .highest-scores {
        height: calc(100vh - 100px);
        background: #7138A8;
        background-image: url('/events/quiz-game/highest-score.png');
        background-repeat: no-repeat;
        text-align: center;
    }

    /* END HIGHEST SCORES */
    /* Extra small devices (phones, less than 768px) */
    @media (max-width: 768px) {
        .answers .body-section {
            padding: 50px 15px;
        }

        .select-answers .answer-box {
            margin: 12px 0;
        }
    }

    /* PREPARE ANSWER */
    .prepare-answer {
        background: #409EFF;
    }

    .prepare-answer .container {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .prepare-answer .loading-icon {
        margin: 50px 0;
    }

    /* END PREPARE ANSWER */

    /* Small devices (tablets, 768px and up) */
    @media (min-width: @screen-sm-min) and (max-width: @screen-sm-max) {
        ...
    }

    /* Medium devices (desktops, 992px and up) */
    @media (min-width: @screen-md-min) and (max-width: @screen-md-max) {
        ...
    }

    /* Large devices (large desktops, 1200px and up) */
    @media (min-width: @screen-lg-min) {
        ...
    }

    /* Loader */
    .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
    }

    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 8px;
        border-radius: 50%;
        border: 6px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }

    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* End loader */
</style>
</head>
