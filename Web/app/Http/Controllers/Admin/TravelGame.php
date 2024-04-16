<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{TravelGame as ModelTravelGame};
use Auth;

class TravelGame extends Controller
{
    public function __construct(
        private ModelTravelGame $travel,
    ) {
        //code
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $limit = $user->role == CLIENT_ROLE ? 30 : 50;
            $travels = $this->travel->with('user');

            if ($user->role == CLIENT_ROLE) {
                $travels = $travels->whereUserId($user->id);
            }
            $travels = $travels
                ->orderBy('status', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('cws.travel.index', [
            'travels' => $travels,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $id = $request->input('id');
            $name = $request->input('name');
            $note = $request->input('note');
            $prize_at = $request->input('prize_at');

            if ($id) {
                $travel = $this->travel->find($id);
                if ($travel) {
                    $travel->update([
                        'name' => $name,
                        'note' => $note,
                        'prize_at' => $prize_at
                    ]);
                }
            } else {
                $this->travel->create([
                    'user_id' => $userId,
                    'name' => $name,
                    'note' => $note,
                    'prize_at' => $prize_at
                ]);
            }
        } catch (\Exception $e) {
            return $this->resError();
        }

        return $this->resOk();
    }

    public function updStatus(Request $request, $id)
    {
        try {
            if (Auth::guest()) {
                return $this->resError();
            }

            $travel = $this->travel->find($id);
            if (!$travel) { return $this->resError(); }
            $travel->update(['status' => $travel->status ? false : true]);
        } catch (\Exception $e) {
            return $this->resError();
        }

        return $this->resOk();
    }

    private function resError()
    {
        return response()->json([
            'message' => 'Errors'
        ], 400);
    }

    private function resOk()
    {
        return response()->json([
            'message' => 'Ok'
        ], 200);
    }
}
