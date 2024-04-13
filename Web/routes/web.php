<?php
use App\Mail\SendNFTMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\NextQuestionEvent;
use App\Events\NotificationEvent;
use App\Events\TotalPointEvent;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Web\{EventController,
    Home,
    Detail,
    Likes,
    Job,
    PagesController,
    QuizGameController,
    UserController};

use App\Http\Controllers\Web\Auth\{
    Login,
    SignUp,
    ForgotPassword
};

Route::post('/login_google', [Login::class, 'apiLogin'])->name('web.login.api');

// Định nghĩa các route cho người dùng chưa đăng nhập (guest)
Route::middleware(['guest'])->group(function ($auth) {
    // Login social
    Route::get('login/{providerName}', [Login::class, 'redirectToProvider']);
    Route::post('login/{providerName}/callback', [Login::class, 'handleProviderCallback']);

    // Login
    $auth->get('login', [Login::class, 'showFormLogin'])->name('web.formLogin');
    $auth->post('login', [Login::class, 'login'])->name('web.login');

    $auth->get('login-guest', [Login::class, 'formLoginGuest'])->name('web.formLoginGuest');
    $auth->post('login-guest', [Login::class, 'loginGuest'])->name('web.loginGuest');

    //API Router
    //API Connect wallet
    /*
     * Sau khi connect xong thì frontend gửi lên backend 2 thông tin:
     * wallet_address và wallet_name (account name: optional)
    Backend xử lý nếu chưa có trong DB thì đăng ký user mới.
    Nếu có rồi thì thôi. Cuối cùng xử lý để sao cho user đó đã ở trạng thái đã login.
     * */
    //Route::post('/api/connect-wallet', [\App\Http\Controllers\FrontendController::class, 'connectWallet'])->name('connect-wallet');

    //Wallet Login
    //Route::any('/api/wallet-login', [\App\Http\Controllers\FrontendController::class, 'walletLogin'])->name('wallet-login');

    // Comfirm register
    // $auth->get('confirm/{token}', [SignUp::class, 'confirmRegis'])->name('')

    // Forgot password
    $auth->get('forget-password', [ForgotPassword::class, 'showForgetPasswordForm'])->name('web.formForgot');
    $auth->post('forget-password', [ForgotPassword::class, 'submitForgetPasswordForm'])->name('forget.password.post');

    // Action reset
    $auth->get('reset-password/{token}', [ForgotPassword::class, 'showResetPasswordForm'])->name('reset.password.get');
    $auth->post('reset-password', [ForgotPassword::class, 'submitResetPasswordForm'])->name('reset.password.post');

    // Sign up
    $auth->get('sign-up', [SignUp::class, 'showSignup'])->name('web.formSignup');
    $auth->post('sign-up', [SignUp::class, 'store'])->name('web.signUp');
});

// Định nghĩa các route cho người dùng đã đăng nhập
Route::middleware(['user_event'])->group(function ($r) {
    $r->get('logout', [Login::class, 'logout'])->name('web.logout');
    $r->get('profile', [UserController::class, 'profile'])->name('web.profile');
    $r->post('editEmail', [UserController::class, 'editEmail'])->name('web.editEmail');

    //Edit update user
    $r->get('editUser', [UserController::class, 'showEditUser'])->name('web.showEditUser');
    $r->post('editUser', [UserController::class, 'editUser'])->name('web.editUser');

    $r->post('editPassword', [UserController::class, 'editPassword'])->name('web.editPassword');

    // Travel
    $r->get('event-job/{id}', [Home::class, 'jobEvent'])->name('web.jobEvent');

    $r->get('info/{task_id}', [Job::class, 'getTravelGame'])->name('job.getTravelGame');

    $r->get('quiz/{code}', [Job::class, 'getJob'])->name('job.getJob');

    // New sponsor
    $r->get('sponsor/new', [Job::class, 'newSponsor'])->name('new.sponsor');
    $r->post('sponsor/pay', [Job::class, 'saveSponsor'])->name('new.saveSponsor');

    $r->get('r/{id}', [Home::class, 'isResult'])->name('r.isResult');
    // remove pop ntf
    $r->get('api/remove-nft', [Job::class, 'removeNft'])->name('nft.remove');

    // scan QR
    $r->get('nft/claim/{id}', [\App\Http\Controllers\Web\ClaimNftController::class, 'claim'])->name('nft.claim');
    $r->get('nft/claim-action/{id}', [\App\Http\Controllers\Web\ClaimNftController::class, 'claimAction'])->name('nft.claimAction');
    $r->get('event-register/{id}', [EventController::class, 'register'])->name('web.events.register');
    $r->get('events/code', [Job::class, 'index'])->name('web.eventCode');

    //user claim session và booth
    $r->post('claim/event', [Job::class, 'index'])->name('web.claim');

});

// Các route không yêu cầu middleware
Route::get('event/{id}', [Home::class, 'show'])->name('web.events.show');
// mint nft
Route::post('update_nft_status', [\App\Http\Controllers\Admin\NFTController::class, 'updateNftClaim'])->name('api.updateStatusNftClaim');

Route::get('/', [Home::class, 'index'])->name('web.home');
Route::get('event-lists', [Home::class, 'events'])->name('web.events');
//Route::get('event/{id}', [Home::class, 'show'])->name('web.events.show');
Route::get('solution', [PagesController::class, 'solution'])->name('web.solution');
Route::get('template', [PagesController::class, 'template'])->name('web.template');
Route::get('pricing', [PagesController::class, 'pricing'])->name('web.pricing');
Route::get('resource', [PagesController::class, 'resource'])->name('web.resource');
Route::get('contact', [PagesController::class, 'contact'])->name('web.contact');
Route::get('lang/{lang}', [PagesController::class, 'lang'])->name('web.lang');
Route::get('callback', [Home::class, 'callbackLogin'])->name('web.callback');
//Route::get('callback', [Home::class, 'callbackLogin'])->name('web.callback');


//Payment success
Route::get('payment-success', [Home::class, 'paymentSuccess'])->name('web.paymentSuccess');


//Order ticket, Send email
Route::post('order/ticket', [Home::class, 'orderTicket'])->name('order.ticket');

//Ticket pdf
Route::get('ticket/pdf/{id?}', [Home::class, 'ticketPdf'])->name('ticket.pdf');

//Prefix API Router

//User Event List API
Route::get('events/user-list/{id}', [Home::class, 'apiUserList'])->name('listUser.Events');

// route for get shortener url
Route::get('{shortener_url}', [\App\Http\Controllers\UrlController::class, 'shortenLink'])->name('shortener-url');

Route::get('event-code/{id}', [\App\Http\Controllers\UrlController::class, 'dayOne'])->name('code-dayOne');

Route::get('event-user/{id}', [\App\Http\Controllers\UrlController::class, 'dayOne'])->name('code-User');

//Flush all url
Route::get('flush-all-url', [\App\Http\Controllers\UrlController::class, 'flushAllUrl'])->name('flush-all-url');

/**
 * Used to preview the Pleb email templates
 * @todo remove these routes before going live
 */
Route::get('pleb/welcome_member', function () {

    $member = User::find(1);
    return new App\Mail\WelcomeMember($member);
});

Route::get('pleb/verify_email', function () {

    $member = User::query()->first();
    $options = array(
        'verify_url' => 'http://gotohere.com',
    );
    return new App\Mail\VerifyEmail($member, $options);
});

Route::get('pleb/thanks_checkin_nft', function () {

    $member = User::query()->first();
    $options = array(
        'verify_url' => 'http://gotohere.com',
        'image_url' => '',
        'event_name' => 'Event Name',
        'event_location' => 'Event Name',
        'invoice_id' => '10087866',
        'invoice_total' => '100.07',
        'download_link' => 'http://google.com',
    );
    return new App\Mail\ThankYouCheckInNFT($member, $options);
});

Route::get('pleb/forgot_password', function () {

    $member = User::query()->first();
    $options = array(
        'reset_link' => 'http://gotohere.com',
    );

    return new App\Mail\ForgotPassword($member, $options);
});

Route::get('pleb/thanks_payment', function () {

    $member = User::query()->first();
    $options = array(
        'invoice_id' => '10087866', 'invoice_total' => '100.07', 'download_link' => 'http://gotohere.com',
    );

    Mail::to($member)->send(new \App\Mail\ThankYouPayment($member, $options));

    return new App\Mail\ThankYouPayment($member, $options);
});

Route::get('pleb/thanks_checkin', function () {

    $member = User::query()->first();
    //Event
    $event = \App\Models\Task::query()
        ->orderBy('created_at', 'DESC')
        ->first();

    $options = array(
        'invoice_id' => '10087866',
        'invoice_total' => '100.07',
        'event_name' => 'Event',
        'event_description' => 'Event',
        'event_location' => 'Event',
        'event_date' => 'date',
        'start' => 'date',
        'end' => 'date',
        'event_time' => 'time',
        //'download_link' => route('web.events.show', $event->id),
        'download_link' => 'https://platsevent.web.app/reward-nft?id='.$event->id,
    );

    //Mail::to($member)->send(new \App\Mail\ThankYouCheckIn($member, $options));

    return new App\Mail\ThankYouCheckIn($member, $options);
});


Route::get('/discord/login', [App\Http\Controllers\Web\Discord::class, 'getUserDiscord']);
Route::get('/discord', [App\Http\Controllers\Web\Discord::class, 'index'])->name('discord');
Route::get('/telegram', [App\Http\Controllers\Web\Discord::class, 'telegram'])->name('telegram');
Route::get('logout-discord', [App\Http\Controllers\Web\Discord::class, 'logout']);
Route::get('events/list', [Home::class, 'webList'])->name('list.Events');
Route::post('create-user', [Job::class, 'createUser'])->name('web.createUser');

//Create Crowd Sponsor Link
Route::any('create-crowd-sponsor', [Home::class, 'createCrowdSponsor'])->name('web.createCrowdSponsor');

//payment-crowdsponsor
Route::get('payment-crowdsponsor', [Home::class, 'paymentCrowdSponsor'])->name('web.paymentCrowdSponsor');


// Route::get('/events/history/list', [Job::class, 'apiList']);
// Route::get('/events/history/user', [Home::class, 'apiList']);
// Route::get('/events/likes', [Likes::class, 'index'])->name('web.like');;
// Route::get('/events/task/{id}', [Detail::class, 'edit'])->whereUuid('id');
// Route::post('/events/likes', [Detail::class, 'like']);
// Route::get('/events/download-ticket/{id}', [Detail::class, 'downloadTicket']);
// Route::get('/events/likes/list', [Detail::class, 'listLike']);
// Route::get('/events/user/ticket', [Detail::class, 'userTicket']);
// Route::post('/events/ticket', [Detail::class, 'addTicket'])->name('web.event.addTicket');
// Route::get('/events/{slug}', [Detail::class, 'index']);


// QUIZ
Route::get('/quiz-game/answers/{eventId}', [QuizGameController::class, 'showAnswers'])->name('quiz-name.answers');
Route::prefix('quiz-game')->group(function () {
    Route::middleware('user_event')->group(function () {
        // Route::get('/answers/{eventId}', [QuizGameController::class, 'showAnswers'])->name('quiz-name.answers');
        Route::post('/send-total-score', [QuizGameController::class, 'sendTotalScore']);
    });
});

// new auth with magiclink
