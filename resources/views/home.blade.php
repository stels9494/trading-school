@extends('layouts.app')

@section('content')
<div id="app">
	@php
		use App\Models\Stock;
		use App\Models\Setting;
		$user = auth()->user();
		$command = $user->command;
	@endphp
    <game-screen
        :stocks_props="{{ Stock::where('on_the_exchange', true)->get() }}"
        :user="{{ $user }}"
        :command="{{ $command }}"
        :status_prop="{{ Setting::getValueByName('status') ? 1 : 0 }}"
        :is_pause_prop="{{ Setting::getValueByName('is_pause') ? 1 : 0 }}"
        :month_in_minute_prop="{{ Setting::getValueByName('month_in_minute') }}"
        :current_date_prop="'{{ Setting::getValueByName('current_date')->format('m.Y') }}'"
        {{-- :balance="{{ $command->balance }}" --}}
{{--        :stocks_balance_prop="{{ $command->stocks_balance }}"--}}
{{--        :stocks_count_prop="{{ $command->stocks->sum('count') }}"--}}
    >

    </game-screen>
</div>
@endsection
