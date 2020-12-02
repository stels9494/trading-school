<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SettingController\SwitchGame;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Carbon\Carbon;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings.index');
    }


    // сохраниь набор настроек, которые пользователь может самостоятельно менять
    // запустить игру

    public function switchGame(SwitchGame $request)
    {
        if (Setting::getValueByName('status'))
        {
            Setting::setValueByName('status', false);
            $msg = 'Игра приостановлена';
        } else {
            Setting::setValueByName('date_trading_start', new Carbon($request->year_start.'-'.$request->month_start));
            Setting::setValueByName('date_trading_finish', new Carbon($request->year_finish.'-'.$request->month_finish));
            Setting::setValueByName('month_in_minute', $request->month_in_minute);
            Setting::setValueByName('current_date', $request->date_trading_start);
            Setting::setValueByName('status', true);
            $msg = 'Игра запущена';
        }

        return back()->with($msg);
    }
}
