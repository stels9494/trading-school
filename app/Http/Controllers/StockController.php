<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Command;

class StockController extends Controller
{
    /**
     * Покупка акции
     */
    public function buy(Request $request, Command $command, Stock $stock)
    {
        $result = $command->buy($stock, $request->count);

        if ($result)
        	return response()->json([
        		'status' => 'ok',
        	]);
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
    	$result = $command->sell($stock, $request->count);
    	
        if ($result)
        	return response()->json([
        		'status' => 'ok',
        	]);
        else 
        	return response()->json([
        		'status' => 'error',
        		'msg' => 'insufficient funds',
        	], 400);
    }
}
