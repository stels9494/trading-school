<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SettingController\SwitchGame;
use App\Http\Controllers\Controller;
use App\Models\Command;
use App\Models\User;
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

    public function switchPause()
    {
        $pause = !Setting::getValueByName('is_pause');
        Setting::setValueByName('is_pause', $pause);

        foreach (\App\Models\Command::query()->get() as $command){
            broadcast(new \App\Events\PauseGame($command));
        }

        if (!$pause){
            //если сняли с паузы
            $currentDate = Setting::getValueByName('current_date');
            // перевести на след месяц
            Setting::setValueByName('current_date', $currentDate->addMonth());

            foreach (\App\Models\Command::query()->get() as $command){
                broadcast(new \App\Events\UpdateCharts($command, [$currentDate]));
            }
        }

        return back();
    }

    // сохраниь набор настроек, которые пользователь может самостоятельно менять
    // запустить игру

    public function switchGame(SwitchGame $request)
    {
        if (Setting::getValueByName('status'))
        {
            Setting::setValueByName('status', false);

            $msg = 'Игра приостановлена';

            foreach (Command::get() as $command){
                broadcast(new \App\Events\StopGame($command));
            }
        } else {
            Setting::setValueByName('date_trading_start', new Carbon($request->year_start.'-'.$request->month_start));
            Setting::setValueByName('date_trading_finish', new Carbon($request->year_finish.'-'.$request->month_finish));
            Setting::setValueByName('month_in_minute', $request->month_in_minute);
            Setting::setValueByName('current_date', new Carbon($request->year_start.'-'.$request->month_start));
            Setting::setValueByName('status', true);
            Setting::setValueByName('is_pause', false);
            $msg = 'Игра запущена';

            foreach (Command::get() as $command){
                broadcast(new \App\Events\StartGame($command));
            }
        }

        return back()->with($msg);
    }
}
