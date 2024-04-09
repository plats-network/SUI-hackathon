<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use \GuzzleHttp;
use Session;
use Auth;
use pschocke\TelegramLoginWidget\Facades\TelegramLoginWidget;

class Discord extends Controller
{
    public function __construct()
    {
        $this->middleware('client_web');
    }

    protected $tokenURL = "https://discordapp.com/api/oauth2/token";
    protected $apiURLBase = "https://discordapp.com/api/users/@me";
    protected $tokenData = [
        "client_id" => NULL,
        "client_secret" => NULL,
        "grant_type" => "authorization_code",
        "code" => 'NhhvTDYsFcdgNLnnLijcl7Ku7bEEeee',
        "redirect_uri" => NULL,
        "scope" => "identifiy&email"
    ];

    public function index()
    {
        $userLogin = \Illuminate\Support\Facades\Auth::user();
        return view('web.socail',['discord' => $userLogin->discord,'telegram' => $userLogin->telegram]);
    }
    public function getUserDiscord(Request $request) {

        $this -> tokenData["client_id"] = env("DISCORD_CLIENT_ID");
        $this -> tokenData["client_secret"] = env("DISCORD_CLIENT_SECRET");
        $this -> tokenData["code"] = $request -> get("code");
        $this -> tokenData["redirect_uri"] = env("DISCORD_REDIRECT_URI");

        $client = new GuzzleHttp\Client();
        $accessTokenData = $client -> post($this -> tokenURL, ["form_params" => $this -> tokenData]);
        $accessTokenData = json_decode($accessTokenData -> getBody());

        $userData = Http::withToken($accessTokenData -> access_token) -> get($this -> apiURLBase);
        if ($userData -> clientError() || $userData -> serverError()) {return ;};

        $userData = json_decode($userData);
        $userLogin = \Illuminate\Support\Facades\Auth::user();
        $user = User::where('id',$userLogin->id)->update(['discord' => $userData->username]);
        return redirect() -> route("discord");
    }

    public function checkUserJoinChannels($channelId,$taskId)
    {
        $channelId = '1099897282922557442';
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET','https://discordapp.com/api/guilds/'.$channelId.'/widget.json');
        $response = json_decode($response);
    }

    public function telegram(Request $request) {
        $userLogin = \Illuminate\Support\Facades\Auth::user();
        $user = User::where('id',$userLogin->id)->update(['telegram' => $request->input('username')]);
        return redirect() -> route("discord");
    }

}
