<?php

namespace App\Http\Controllers\Web;

use App\Exports\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddTicketRequest;
use App\Models\Event\EventShare;
use App\Models\Event\TaskEvent;
use App\Models\Task;
use App\Repositories\EventUserTicketRepository;
use App\Repositories\UserEventLikeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Route, Mail, Auth};
use App\Jobs\SendTicket;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BaseImage;
use Str;

class Detail extends Controller
{
    public function __construct(
        EventUserTicketRepository $eventUserTicketRepository,
        UserEventLikeRepository $eventLikeRepository
    )
    {
        $this->repository = $eventUserTicketRepository;
        $this->userEventLikeRepository = $eventLikeRepository;
    }

    public function index(Request $request)
    {
        $keyLocal = '';
        if (
            $request->input('facebook')
            || $request->input('twitter')
            || $request->input('telegram')
            || $request->input('discord')
            || $request->input('form')
        ) {
            foreach ($request->all() as $key => $part) {
                $keyLocal= $key;
            }
        }

        $getIdTask = Task::where('slug', Route::current()->slug)->first();

        if ($getIdTask){
            return view('web.detail',['id' => $getIdTask->id,'key'=>$keyLocal]);
        }
        return $this->respondSuccess('false');
    }

    public function addTicket(AddTicketRequest $request)
    {
        try {
            $user = Auth::user();
            $data = Arr::except($request->all(), 'share');
            if ($data['phone'] == null){
                $data['phone'] = 0;
            }
            $task = Task::findOrFail($data['task_id']);
            $data['type'] = $user ? 0 : 1;
            $data['user_id'] = $user ?  $user->id : null;
            $data['hash_code'] = Str::random(32);
            $urlQR = env('LINK_CWS').'/api/events/confirm-ticket?type=checkin&id=' . $data['hash_code'];
            $image = \QrCode::format('png')
                ->size(100)
                ->generate($urlQR);

            $output_file = '/img/qr-code/img-' . $data['hash_code'] . '.png';
            $files = Storage::disk('s3')->put($output_file, ($image));
            $files = Storage::disk('s3')->url($output_file);
            $data['qr_image'] = BaseImage::imgGroup($files);

            $checkSendMail = $this->repository
                ->whereEmail($data['email'])
                ->whereTaskId($data['task_id'])
                ->first();

            if ($checkSendMail) {
                dispatch(new SendTicket($task, $data['email'], $checkSendMail));
            } else {
                session()->put('hash_code', $data['hash_code']);
                $this->repository->create($data);
                if ($request->input('share')){
                    $share = [
                        'task_id' => $data['task_id'],
                        'email' => $data['email'],
                        'name' => $data['name'],
                        'type' => $this->getTypeShare($request->input('share')),
                    ];
                    EventShare::create($share);
                }

                $user = $this->repository->whereHashCode($data['hash_code'])->first();
                dispatch(new SendTicket($task, $data['email'], $user));
            }

            return $this->respondSuccess('Success');
        } catch (\Exception $e) {
            return $this->respondError('Errors save data', 422);
        }
    }

    public function edit($id)
    {
        try {
            $task = Task::find($id);
            $booths = TaskEvent::where('task_id',$id)->with('detail')->where('type',1)->first();
            $sessions = TaskEvent::where('task_id',$id)->with('detail')->where('type',0)->first();
            $task['booths'] = $booths;
            $task['sessions'] = $sessions;
        } catch (\Exception $e) {
            return $this->respondError('Errors', 500);
        }

        return $this->respondSuccess($task);
    }

    public function like(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $data = Arr::except($request->all(), '__token');
            $check = $this->userEventLikeRepository
                ->where('user_id', $userId)
                ->where('task_id',$data['task_id'])
                ->first();

            if ($check){
                $dataBaseTask = $this->userEventLikeRepository
                    ->where('user_id', $userId)
                    ->where('task_id',$data['task_id'])
                    ->delete();

                return $this->respondSuccess($dataBaseTask);
            }

            $data['user_id'] = $userId;
            $dataBaseTask = $this->userEventLikeRepository->create($data);
        } catch (\Exception $e) {
            return $this->respondError('Errors', 500);
        }

        return $this->respondSuccess($dataBaseTask);
    }

    public function listLike(Request $request)
    {
        $input= Arr::except($request->all(), '__token');
        $data = $this->userEventLikeRepository->where('user_id',Auth::user()->id)
            ->join('tasks', 'tasks.id', '=', 'user_event_likes.task_id')
            ->orderBy('user_event_likes.created_at', 'desc')
            ->paginate(20);

        return response()->json($data);

    }

    public function downloadTicket($id)
    {
        $task = Task::find($id);
        $code = session()->get('hash_code');
        $user = $this->repository->whereHashCode($code)->first();
//        return view('mails.send_ticket',['ticket'=> $task,'user' =>$user]);
        return (new Ticket($task,$user))->downloadPdf();
    }

    public function getTypeShare($type)
    {
        switch ($type) {
            case "facebook":
                $data = 0;
                break;
            case "twitter":
                $data = 1;
                break;
            case "telegram":
                $data = 2;
                break;
            case "discord":
                $data = 3;
                break;
            default:
                $data = 4;
        }
        return $data;
    }
}
