<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {

    }

    public function show(Request $request, Stock $stock){
        switch($request->action){
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
                foreach ($quotations as $quotation){
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
}
