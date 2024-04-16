<?php

namespace App\Http\Controllers\Web;

use App\Events\NextQuestionEvent;
use App\Events\SummaryResultQuizGameEvent;
use App\Events\UserJoinQuizGameEvent;
use App\Http\Controllers\Controller;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizResult;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Cache;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class QuizGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request The request object.
     * @param string $eventId The UUID of the event.
     */
    public function index(Request $request, $eventId): mixed
    {
        //Is show board
        $isShowBoard = $request->get('board', false);

        $isUuid = Str::isUuid($eventId);
        // Validate uuid event id
        if (!$isUuid) {
            return redirect()->route(DASHBOARD_WEB_ROUTER);
        }

        $event = $this->getEventById($eventId);
        $totalQuiz = $event->quizs->count();
        // Check event not found
        if (!$event) {
            return redirect()->route(DASHBOARD_WEB_ROUTER);
        }

        $urlAnswers = route('quiz-name.answers', $eventId);
        $qrCode = QrCode::format('png')->size(300)->generate($urlAnswers);
        $listJoinedUsers = Cache::get('list_joined_users_' . $eventId) ?? [];


        return view('quiz-game.questions', compact('qrCode', 'event', 'totalQuiz', 'listJoinedUsers'));
    }

    /**
     * Show answers screen
     *
     * @param Request $request The request object.
     * @param string $eventId The UUID of the event.
     */
    public function showAnswers(Request $request, $eventId): mixed
    {
        if (!Auth::check()) {
            session()->put('guest', [
                'id' => $eventId,
                'type' => 'quiz'
            ]);

            return redirect()->route('web.formLoginGuest');
        }

        $isUuid = Str::isUuid($eventId);
        // Validate uuid event id
        if (!$isUuid) {
            return redirect()->route(DASHBOARD_WEB_ROUTER);
        }

        $user = $request->user();
        $event = $this->getEventById($eventId);

        if (!$event) {
            notify()->error('Event not found!');
            return redirect()->route('web.home');
        }

        $listJoinedUsers = Cache::get('list_joined_users_' . $eventId) ?? [];
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'point' => 0
        ];
        $isUserJoined = false;
        // Check is user exist in cache
        foreach ($listJoinedUsers as $userJoined) {
            if ($user->id === $userJoined['id']) {
                $isUserJoined = true;
            }
        }

        if (!$isUserJoined || !$listJoinedUsers) {
            array_push($listJoinedUsers, $data);
        }

        Cache::put('list_joined_users_' . $eventId, $listJoinedUsers, now()->addMinutes(60));

        // Notify new user join and show total users
        event(new UserJoinQuizGameEvent($listJoinedUsers, $eventId));

        return view('quiz-game.answers', [
            'eventId' => $eventId,
            'userName' => $user->name,
            'userId' => $user->id,
            'eventName' => $event->name,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request The request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data.
     */
    public function getQuestionByNumber(Request $request)
    {
        $questionNumber = (int)$request->questionNumber;
        $eventId = $request->eventId;
        $question = Quiz::with('detail')
            ->where('task_id', $eventId)
            ->skip((int)$questionNumber - 1)
            ->take(1)
            ->first();

        $correctAnswerKey = $question->detail->filter(function ($answer) {
            return $answer->status;
        })->first();

        $data = [
            'id' => $question->id,
            'number' => $questionNumber,
            'name' => 'Question number ' . $questionNumber . ' : ' . $question->name,
            'answers' => $question->detail,
            'correctAnswer' => $correctAnswerKey->id ?? null,
            'timeToAnswer' => $question->time_quiz,
            'totalQuestion' => Quiz::where('task_id', $eventId)->count(),
            'image' => $request->getSchemeAndHttpHost() . '/events/quiz-game/' . rand(1, 4) . '.jpg'
        ];
        event(new NextQuestionEvent($data, $request->eventId));

        return response()->json([$data]);
    }

    /**
     * User send total score after finish the quiz
     *
     * @param Request $request The request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the message.
     */
    public function sendTotalScore(Request $request)
    {
        $user = $request->user();

        // Save point to DB
        QuizResult::updateOrCreate(
            [
                'task_id' => $request->eventId,
                'user_id' => $user->id
            ],
            [
                'point' => $request->totalPoint,
                'answer_id' => $request->answerId
            ]
        );

        return response()->json(['message' => 'Send point successful.']);
    }

    /**
     * Get scoreboard in the end of the game
     *
     * @param Request $request The request object.
     * @param string $eventId The UUID of the event.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data.
     */
    public function getScoreboard(Request $request, $eventId)
    {
        //Response type. 1 json, 2 html
        $responseType = $request->show_type ?? 1;

        define('TOP_HIGHEST_SCORE', 10);
        $scoreboard = QuizResult::with('user:name,id')
            ->select('id', 'user_id', 'point')
            ->where('task_id', $eventId)
            ->orderBy('point', 'DESC')
            ->get();

        // Notify rank to players
        if ($request->isLastQuestion === 'true') {
            event(new SummaryResultQuizGameEvent($scoreboard, $request->eventId));
        }

        $data = [
            'topScoreboard' => $scoreboard->take(TOP_HIGHEST_SCORE),
            'scoreboard' => $scoreboard
        ];
        //If $responseType = 2, return html
        if ($responseType == 2) {
            return view('quiz-game.scoreboard', $data);
        }

        return response()->json($data);
    }

    /**
     * Get event by id
     *
     * @param string $eventId The UUID of the event
     * @return Task The retrieve instance model
     */
    public function getEventById($eventId)
    {
        return Task::where('id', $eventId)->first();
    }

    /**
     * Get summary results answers
     *
     * @param Request $request The request object.
     * @param string $eventId The UUID of the event
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data.
     */
    public function getSummaryResults(Request $request, $eventId)
    {
        $quizAnswerResults = QuizAnswer::withCount('quizResults')->where('quiz_id', $request->quiz_id)->get();
        $totalQuizResultsCount = $quizAnswerResults->sum('quiz_results_count');

        // Reset answers
        QuizResult::where('task_id', $eventId)->update(['answer_id' => null]);

        return response()->json([
            'summaryAnswer' => $quizAnswerResults,
            'totalAnswered' => $totalQuizResultsCount
        ]);
    }
}
