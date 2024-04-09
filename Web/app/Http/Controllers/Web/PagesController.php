<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function __construct() {}

    public function solution(Request $request)
    {
        return view('web.solution');
    }

    public function template(Request $request)
    {
        return view('web.template');
    }

    public function pricing(Request $request)
    {
        return view('web.pricing');
    }

    public function resource(Request $request)
    {
        return view('web.resource');
    }

    public function contact(Request $request)
    {
        return view('web.contact');
    }

    public function lang(Request $request, $lang)
    {
        app()->setLocale($lang);
        session()->put('locale', $lang);

        return redirect()->back();

        // app()->setLocale($lang);
        // session(['locale' => $lang]);
        // setlocale(LC_TIME, $lang);
        // Carbon::setLocale($lang);
        // $request->session()->flash('status', 'Language changed to'.' '.strtoupper($lang));
        // return redirect()->back()->with('msg', 'Language changed to'.' '.strtoupper($lang));
    }
}
