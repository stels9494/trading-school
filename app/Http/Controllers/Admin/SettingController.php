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

    public function switchPause(Request $request)
    {
        $pause = !Setting::getValueByName('is_pause');
        Setting::setValueByName('is_pause', $pause);



        if (!$pause){
            //если сняли с паузы
            $currentDate = Setting::getValueByName('current_date');
            // перевести на след месяц
            Setting::setValueByName('current_date', $currentDate->addMonth());
            if ($request->month_in_minute)
                Setting::setValueByName('month_in_minute', $request->month_in_minute);

            foreach (\App\Models\Command::query()->get() as $command){
                broadcast(new \App\Events\UpdateCharts($command, [$currentDate]));
            }
        }

        foreach (\App\Models\Command::query()->get() as $command){
            broadcast(new \App\Events\PauseGame($command));
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
            $dateTradingStart = (new Carbon())->setYear($request->year_start)->setMonth($request->month_start)->setDay(1)->setTime(0, 0, 1);
            $dateTradingFinish = (new Carbon())->setYear($request->year_finish)->setMonth($request->month_finish)->setDay(1)->setTime(0, 0, 1);
            Setting::setValueByName('date_trading_start', $dateTradingStart);
            Setting::setValueByName('date_trading_finish', $dateTradingFinish);
            Setting::setValueByName('month_in_minute', $request->month_in_minute);
            Setting::setValueByName('current_date', $dateTradingStart);
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
