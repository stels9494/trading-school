<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CommandController\Update;
use App\Http\Requests\Admin\CommandController\Store;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Command;

class CommandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commands = Command::all();
        return view('admin.commands.index', ['data' => compact('commands')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.commands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $command = Command::create([
            'name' => $request->name,
            'balance' => $request->balance,
        ]);

        return redirect()
            ->route('admin.commands.index')
            ->with('success', 'Команда создана');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Command $command)
    {
        return view('admin.commands.show', ['data' => compact('command')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Command $command)
    {
        return view('admin.commands.edit', ['data' => compact('command')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Command $command)
    {
        $command->update([
            'name' => $request->name,
            'balance' => $request->balance,
        ]);

        return back()->with('success', 'Данные команды обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Command $command)
    {
        $command->delete();
        return redirect()->route('admin.commands.index')->with('success', 'Команда удалена');
    }

    public function clearHistories(Request $request, Command $command)
    {
        $command->tradingHistories()->delete();
        return redirect()->route('admin.commands.show', $command)->with('success', 'История очищена');
    }

    /**
     * 
     */
    public function export()
    {
        $export = Command::export();

        $response =  new StreamedResponse(
            function () use ($export) {
                $export->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="export-margin-call-'.now().'.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}
