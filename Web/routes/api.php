<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    Task, TaskLocation, UserTask,
    Wallet,
    QrCode, TaskNotice,
    Twitter, Social,TaskV2, Group,
    UserReward, Event
};

// Admin CWS
use App\Http\Controllers\Api\Admin\{
    Reward,
    Tasks,
    Event as CwsEvent,
    Group as CwsGroup
};
use App\Http\Controllers\Auth\Api\{
    Register,
    Login,
    RegisterAdmin
};
use App\Http\Controllers\Api\{
    Profile,
    ResetPassword,
    User
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// NEW
Route::post('register_admin', [RegisterAdmin::class, 'store']);
Route::post('register', [Register::class, 'register']);
Route::post('register/confirm-email', [Register::class, 'confirmEmail']);
Route::post('register/resend-confirm-email', [Register::class, 'resendOtp']);
// Route::get('/', function() {
//     abort(403);
// });
//Ping https://api.plats.test/api/ping
Route::get('ping', function() {
    return response()->json([
        'message' => 'pong'
    ]);
});

//API Router
//API Connect wallet
/*
 * Sau khi connect xong thì frontend gửi lên backend 2 thông tin:
 * wallet_address và wallet_name (account name: optional)
Backend xử lý nếu chưa có trong DB thì đăng ký user mới.
Nếu có rồi thì thôi. Cuối cùng xử lý để sao cho user đó đã ở trạng thái đã login.
 * */
Route::post('connect-wallet', [\App\Http\Controllers\FrontendController::class, 'connectWallet'])->name('connect-wallet');

//Wallet Login
Route::any('wallet-login', [\App\Http\Controllers\FrontendController::class, 'walletLogin'])->name('wallet-login');


// Login
Route::post('login', [Login::class, 'login'])->name('api.login');
Route::post('login/social', [Login::class, 'socialLogin'])->name('api.socialLogin');
Route::get('login/apple', [Login::class, 'loginApple'])->name('api.loginApple');
Route::post('login/{providerName}/callback', [Login::class, 'callbackProvider'])->name('api.callbackProvider');

// Reset password
Route::post('reset-password', [ResetPassword::class, 'sendMail']);
Route::post('check-recovery-code', [ResetPassword::class, 'verifyCode']);
Route::put('reset-password', [ResetPassword::class, 'reset']);
Route::get('ticket/{id}', [User::class, 'getTicket'])->name('user.getTicket');

Route::middleware('auth:api')->group(function ($router) {
    $router->post('refresh', [Login::class, 'refresh']);
    $router->get('account-socials', [User::class, 'getAccountSocial'])->name('account.social');
    $router->delete('delete-account', [User::class, 'deleteAccount'])->name('delete.account');
    $router->prefix('profile')->group(function ($router) {
        $router->get('/', [Profile::class, 'index']);
        $router->put('/', [Profile::class, 'update']);
        $router->post('/upload-avatar', [Profile::class, 'updateAvatar']);
        $router->post('/password', [Profile::class, 'changePassword']);
        $router->post('/update-social', [Profile::class, 'updateSocialAccount']);
    });
    $router->prefix('wallet')->group(function ($router) {
        $router->post('withdraw', [Wallet::class, 'withdraw']);
    });

    $router->get('tickets', [Task::class, 'listTicks'])->name('user.listTickets');

    Route::prefix('tasks')->controller(Task::class)->group(function ($router) {
       $router->get('/', 'index');
       $router->get('/doing', 'getTaskDoing');
       $router->post('/', 'create');
       $router->get('/{id}', 'detail')->whereUuid('id');
       $router->post('/{id}/check-in/{location_id}', 'checkIn')->whereUuid('id')->whereUuid('location_id');
       $router->patch('/{id}/cancel', 'cancel')->whereUuid('id');
       $router->post('/{id}/social/{social_id}', [Social::class, 'update'])->name('task.social.update');
       $router->post('like-pin', 'taskAction')->name('task.action');
       $router->post('start-cancel', 'startTask')->name('task.startTask');
       $router->get('my-tasks', 'myTasks')->name('task.myTasks');
       $router->post('start-job', 'startJob')->name('task.startJob');

       $router->prefix('{id}/locations')->controller(TaskLocation::class)->group(function ($router) {
           $router->post('/', 'create');
       });
    });

    Route::get('event-imprgress', [Event::class, 'index'])->name('event.improgress');
    Route::get('jobs/{id}', [Event::class, 'show'])->name('event.show.jobs');

    Route::get('top-events', [Task::class, 'getEventTaskHots'])->name('task.event.top-events');

    Route::prefix('qr')->controller(QrCode::class)->group(function($router) {
        $router->post('qr-event', 'qrEvent')->name('qr.qrEvent');
    });

    Route::resource('task_notices', TaskNotice::class)->only(['index']);
    Route::get('get_task', [TaskNotice::class, 'getTask']);
    Route::resource('twitter', Twitter::class)->only(['index', 'store']);
    Route::resource('socials', Social::class)->only(['index']);
    Route::post('social-start/{id}', [Social::class, 'start'])->name('social.start.task');

    Route::prefix('wallet')->controller(Wallet::class)->group(function () {
        Route::post('/withdraw', 'withdraw');
    });

    Route::resource('groups', Group::class)->only(['index', 'show']);
    Route::get('my-groups', [Group::class, 'myGroups'])->name('group.my-groups');
    Route::post('join-group', [Group::class, 'joinGroup'])->name('group.join-group');
    Route::resource('user-rewards', UserReward::class)->only(['index']);
});


