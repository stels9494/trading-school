<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserController\Update;
use App\Http\Requests\Admin\UserController\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Command;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Command $command)
    {
        $users = User::where('command_id', $command->id)->get();

        return view('admin.users.index', ['data' => compact('users')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Command $command)
    {
        return view('admin.users.create', ['data' => compact('command')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Command $command)
    {
        $user = User::create([
            'login' => $request->login,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'patronymic' => $request->patronymic,
            'password' => $request->password,
            'command_id' => $command->id,
        ])->assignRole('member');

        return redirect()->route('admin.commands.show', $command)->with('success', 'Пользователь создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Command $command, User $user)
    {
        return view('admin.users.show', ['data' => compact('command', 'user')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Command $command, User $user)
    {
        return view('admin.users.edit', ['data' => compact('command', 'user')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Command $command, User $user)
    {
        // обновление данных пользователя или перенести в другую команду
        $user->update([
            'login' => $request->login ?? $user->login,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'patronymic' => $request->patronymic,
            'password' => $request->password,
            'command_id' => $request->command_id ?? $command->id,
            'commander' => $request->commander ?? false,
        ]);

        return redirect()->route('admin.users.edit', [$command, $user])->with('success', 'Данные пользователя обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Command $command, User $user)
    {
        if (!$user->hasRole('admin'))
            $user->delete();
        return redirect()->route('admin.commands.show', $command)->with('success', 'Пользователь удален');
    }

    public function setCommander(Request $request, Command $command, User $user)
    {
        $user->commander = $request->commander;
        return response()->json([
            'is_commander' => $user->commander,
        ]);
    }
}
