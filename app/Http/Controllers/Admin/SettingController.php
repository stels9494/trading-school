<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }




    // сохраниь набор настроек, которые пользователь может самостоятельно менять
    // запустить игру

    public function startGame()
    {
        // если игра не запущена - задать начальные значения и запустить игру
        if (!Setting::getValueByName('status'))
        {
            // переводим состояние
            Setting::setValueByName('status', true);
            // задаем ремя начала торговли
            Setting::setValueByName('current_date', Setting::getValueByName('date_trading_start'));
        }
        return response()->json(['status' => 'ok', 'msg' => 'Игра стартовала']);
    }

    /**
     * приостановка игры
     */
    public function stopGame()
    {
        Setting::setValueByName('status', false);
        return response()->json(['status' => 'ok', 'msg' => 'Игра остановлена']);
    }




}
