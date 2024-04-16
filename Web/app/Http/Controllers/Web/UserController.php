<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Event\EventUserTicket;

class UserController extends Controller
{
    public function __construct(
        private User $user,
        private EventUserTicket $ticket,
    )
    {
        // code
    }

    public function profile(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user && $user->role != USER_ROLE) {
                Auth::login($user);
            }
        } catch (\Exception $e) {
            notify()->error('Có lỗi xảy ra');
            Log::error('Errors: '. $e->getMessage());

            return redirect()->route('web.home');
        }

        return view('web.user.profile', [
            'user' => $user,
        ]);
    }

    public function editEmail(Request $request)
    {
        try {
            $user = Auth::user();
            $taskId = $request->input('task_id');
            $email = Str::lower($request->input('email'));
            $name = $request->input('name');

            $oldUser = User::whereRaw('lower(email) = ? ', [$email])->first();
            $oldId = optional($oldUser)->id;
            $ticket = $this->ticket
                ->whereUserId($oldId)
                ->whereTaskId($taskId)
                ->first();
            $tt = $this->ticket
                ->whereUserId(optional($user)->id)
                ->whereTaskId($taskId)
                ->first();

            if (Str::lower($user->email) == $email) {
                notify()->error('Duplicate current email account');

                return redirect()->route('job.getTravelGame', [
                    'task_id' => $taskId
                ]);
            }

            if ($oldUser) {
                notify()->success('Update email successfully');
                $oldUser->update(['email' => 'delete_'.Carbon::now()->timestamp.$oldUser->email]);
                $user->update(['name' => $name, 'email' => $email]);
            } else {
                $user->update([
                    'name' => $name,
                    'email' => (string)$email
                ]);
                notify()->success('Update email successfully');
            }

            if (session()->get('u-'.$user->id)) {
                session()->forget('u-'.$user->id);
            }

            if ($ticket) {
                $tt = $this->ticket
                    ->whereUserId(optional($user)->id)
                    ->whereTaskId($taskId)
                    ->first();
                if ($tt) {
                    $tt->update(['is_vip' => $ticket->is_vip]);
                }
            }
        } catch (\Exception $e) {
            notify()->error('Lỗi: '.$e->getMessage());
            return redirect()->route('job.getTravelGame', [
                'task_id' => $taskId
            ]);
        }

        return redirect()->route('job.getTravelGame', [
            'task_id' => $taskId
        ]);
    }

    //showEditUser
    public function showEditUser(Request $request)
    {
        try {
            $user = Auth::user();
        } catch (\Exception $e) {
            notify()->error('Có lỗi xảy ra');
            Log::error('Errors: '. $e->getMessage());

            return redirect()->route('web.home');
        }

        return view('web.user.editUser', [
            'user' => $user,
        ]);
    }

    //editUser
    public function editUser(Request $request)
    {
        try {
            $user = Auth::user();
            $name = $request->input('name');
            $user->update(['name' => $name]);
            notify()->success('Update user successfully');
        } catch (\Exception $e) {
            notify()->error('Lỗi: '.$e->getMessage());
            return redirect()->route('web.showEditUser');
        }

        return redirect()->route('web.showEditUser');
    }


}
