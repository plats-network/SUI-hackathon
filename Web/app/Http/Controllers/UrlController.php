<?php

namespace App\Http\Controllers;

use App\Models\Event\TaskEvent;
use App\Models\Event\TaskEventDetail;
use App\Models\Event\UserCode;
use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('urls.index', [
            'urls' => Url::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'original_url' => 'required|string|max:255',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['title'] = Str::ucfirst($request->title);
        $data['original_url'] = $request->original_url;
        $data['shortener_url'] = Str::random(5);
        Url::create($data);

        return redirect(route('urls.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function show(Url $url)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function edit(Url $url)
    {
        return view('urls.edit', [
            'url' => $url,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Url $url)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'original_url' => 'required|string|max:255',
        ]);
        $validated['shortener_url'] = Str::random(5);
        $url->update($validated);
        return redirect(route('urls.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url)
    {
        $url->delete();
        return redirect(route('urls.index'));
    }

    public function shortenLink($shortener_url)
    {
        $find = Url::where('shortener_url', $shortener_url)->first();
        if (!$find) {
            return redirect(route('web.events'));
        }

        return redirect()->to($find->original_url);
    }
    //flushAllUrl
    public function flushAllUrl()
    {
        Url::truncate();
        dd('Flush all url success');
    }

    public function dayOne(Request $request, $task_id)
    {
        try {
            $codes = [];
            $eventSession = TaskEvent::query()->whereTaskId($task_id)->whereType(TASK_SESSION)->first();
            $eventBooth = TaskEvent::query()->whereTaskId($task_id)->whereType(TASK_BOOTH)->first();
            $sessions = TaskEventDetail::query()->whereTaskEventId($eventSession->id)->orderBy('sort', 'asc')->get();
            foreach ($sessions as $session){

            }

            $limit=1000;
            $codes =UserCode::query()
                ->where('task_event_id', $session->id)
                //->where('travel_game_id', '9a13167f-4a75-4a46-aa5b-4fb8baea4b9b')
                //->whereIn('user_id', $userIds)
                ->whereType(0)
                ->inRandomOrder()
                ->pluck('number_code')
                ->toArray();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error datas',
                'data' => null
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful',
            'data' => $codes
        ], 200);
    }
}
