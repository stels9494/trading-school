<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Command;
use App\Models\Stock;
use App\Models\CommandStock;

class MainController extends Controller
{
    public function index()
    {
    	$usersCount = User::where('id', '<>', 1)->count();
    	$commandsCount = Command::count();
    	$stocksCount = Stock::count();
    	$tradesCount = CommandStock::count();
    	$stocksOnExchangeCount = Stock::where('on_the_exchange', true)->count();
    	$stocksWithoutQuotations = Stock::doesntHave('quotations')->count();
    	return view('admin.main.index', ['data' => compact('usersCount', 'commandsCount', 'stocksCount', 'tradesCount', 'stocksOnExchangeCount', 'stocksWithoutQuotations')]);
    }
}
