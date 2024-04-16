<?php

namespace App\Http\Controllers\Api;

use App\Helpers\BaseImage;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Resources\SocialResource;
use App\Models\{Event\EventUserTicket, User as UserModel, Task};
use App\Exports\Ticket;
use App\Jobs\SendTicket;
use Illuminate\Support\Facades\Storage;
use Str;

class User extends ApiController
{
    public function __construct (
        private UserModel $user,
        private Task $task
    )
    {
        // code auth
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccountSocial(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $infoUser = $this->user->find($userId);
        } catch (\Exception $e) {
            return $this->respondError("Errors {$e->getMessage()}");
        }

        return $this->respondWithResource(new SocialResource($infoUser), 'Social info');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();
            $user->email = $user->id . '-'. $user->email;
            $user->save();
            $user->delete();
        } catch (\Exception $e) {
            return $this->respondError("Errors {$e->getMessage()}");
        }

        return $this->responseMessage('Delete account successful!');
    }

    public function getTicket($id, Request $request)
    {
        try {
            $user = $request->user();
            $task = $this->task->findOrFail($id);

            $checkTicket = EventUserTicket::whereUserId($user->id)
                ->whereTaskId($task->id)
                ->first();
            $hashCode = Str::random(15);
            $urlQR = env('LINK_CWS').'/events/confirm-ticket?type=checkin&id=' . $hashCode;
            $image = \QrCode::format('png')
                ->size(100)
                ->generate($urlQR);

            $output_file = '/img/qr-code/img-' . $hashCode . '.png';
            $files = Storage::disk('s3')->put($output_file, ($image));
            $files = Storage::disk('s3')->url($output_file);
            $imageQrc = BaseImage::imgGroup($files);

            if (!$checkTicket) {
                EventUserTicket::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'task_id' => $task->id,
                    'type' => 0,
                    'is_checkin' => true,
                    'hash_code' => $hashCode,
                    'qr_image' => $imageQrc,
                ]);
            }

            if ($user) {
                $userTicket = EventUserTicket::whereUserId($user->id)
                    ->whereTaskId($task->id)
                    ->first();

                if ($userTicket) {
                    dispatch(new SendTicket($task, $user->email, $user));
                }
            }
        } catch (\Exception $e) {
            return $this->respondError("Errors {$e->getMessage()}");
        }
        return $this->respondError('Ticket not found', 404);
    }
}
