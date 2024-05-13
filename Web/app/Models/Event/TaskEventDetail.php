<?php

namespace App\Models\Event;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event\TaskEvent;
use App\Models\Traits\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskEventDetail extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_event_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'task_event_id',
        'travel_game_id',
        'name',
        'description',
        'status',
        'code',
        'is_required',
        'is_question',
        'nft_link',
        'sort',
        'question',
        'a1', 'a2', 'a3', 'a4',
        'is_a1', 'is_a2', 'is_a3', 'is_a4',
        'amount',
        'avaliable_amount',
        'photo',
        'contract_task_events_details_id'
    ];

    //Get qr url
    //$qr = 'http://'.config('plats.event').'/events/code?type=event&id='.$session->code;
    public function getQrUrlAttribute()
    {
        $url = 'https://' . config('plats.event') . '/events/code?type=event&id=' . $this->code;

        //Shorten url
        // $shortenUrl = Url::shortenUrl($url, 1);

        return $url;
    }

    //shortenUrl
    //Save url by code key
    public function shortenUrl($url, $code)
    {
        //Check url exist
        $urlCheck = Url::query()
            ->where('original_url', $url)
            ->first();
        if ($urlCheck) {
            return route('shortener-url', ['shortener_url' => $urlCheck->shortener_url]);
        }
        $data['user_id'] = Auth::user()->id;
        $data['title'] = $code;
        $data['original_url'] = $url;
        $data['shortener_url'] = Str::random(5);

        $model = Url::create($data);

        return $data['shortener_url'];
    }
}
