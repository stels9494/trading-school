<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Http\Requests\Admin\StockController\Update;
use App\Http\Requests\Admin\StockController\Store;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::all();
        return view('admin.stocks.index', ['data' => compact('stocks')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.stocks.show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $stock = Stock::create([
            'name' => $request->string,
            'on_the_exchange' => $request->on_the_exchange ?? false,
        ]);

        return redirect()->route('admin.stocks.edit', $stock)->with('success', 'Акция была создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        return view('admin.stocks.show', ['data' => compact('stock')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        return view('admin.stocks.edit', ['data' => compact('stock')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Stock $stock)
    {
        $stock = Stock::create([
            'name' => $request->name,
            'on_the_exchange' => $request->on_the_exchange ?? false,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('admin.stocks.index')->with('success', 'Акция удалена');
    }
}
