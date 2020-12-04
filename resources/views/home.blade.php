@extends('layouts.app')

@section('content')
<div id="app">
    <game-screen
        :stocks="{{ \App\Models\Stock::where('on_the_exchange', true)->get() }}"
        :user="{{ auth()->user() }}"
        :command="{{ auth()->user()->command }}"
    >

    </game-screen>
</div>
@endsection
