<?php

namespace App\Http\Controllers;

use App\Events\CommandBuySell;
use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Command;

class StockController extends Controller
{
    
    public function show(Request $request, Stock $stock)
    {
        switch ($request->action) {
            case 'data':

                break;
            case 'quotations':
                $user = auth()->user();
                $data = [
                    'price' => 0,
                    'portfel' => $user->command->stocks->where('stock_id', $stock->id)->first()->count ?? 0,
                    'history' => [],
                    'trading_history' => $user->command->tradingHistories()->where('stock_id', $stock->id)->orderBy('id', 'desc')->get()
                ];
                $quotations = $stock->quotations()->where('datetime', '<=', Setting::getValueByName('current_date'))->get();
                foreach ($quotations as $quotation) {
                    $data['price'] = $quotation->price;
                    $data['history'][] = [
                        $quotation->datetime->timestamp * 1000,
                        $quotation->price
                    ];
                }
                return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
                break;
        }
    }

    public function buy(Request $request, Command $command, Stock $stock)
    {
        if (Setting::getValueByName('is_pause')){
            return response()->json([
                'status' => 'error',
                'msg' => 'insufficient funds',
            ], 400);
        }
        $result = $command->buy($stock, $request->count);
        $user = auth()->user();

        if ($result) {
            broadcast(new \App\Events\CommandBuySell('buy', $stock, $command))->toOthers();
            return response()->json([
                'status' => 'ok',
                'portfel' => $user->command->stocks->where('stock_id', $stock->id)->first()->count ?? 0,
                'command' => $command,
                'trading_history' => $user->command->tradingHistories()->where('stock_id', $stock->id)->orderBy('id', 'desc')->get()
            ]);
        }
        else
        	return response()->json([
        		'status' => 'error',
        		'msg' => 'insufficient funds',
        	], 400);
    }


    /**
     * Продажа акции
     */
    public function sell(Request $request, Command $command, Stock $stock)
    {
        if (Setting::getValueByName('is_pause')){
            return response()->json([
                'status' => 'error',
                'msg' => 'insufficient funds',
            ], 400);
        }

    	$result = $command->sell($stock, $request->count);
        $user = auth()->user();

        if ($result){
            broadcast(new \App\Events\CommandBuySell('sell', $stock, $command))->toOthers();
            return response()->json([
                'status' => 'ok',
                'portfel' => $user->command->stocks->where('stock_id', $stock->id)->first()->count ?? 0,
                'command' => $command,
                'trading_history' => $user->command->tradingHistories()->where('stock_id', $stock->id)->orderBy('id', 'desc')->get()
            ]);
        }
        else
        	return response()->json([
        		'status' => 'error',
        		'msg' => 'insufficient funds',
        	], 400);
    }
}
