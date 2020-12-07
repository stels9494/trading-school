@extends('layouts.admin')

@section('content')

	@php
		$status = \App\Models\Setting::getValueByName('status');
		$currentDate = \App\Models\Setting::getValueByName('current_date');
	@endphp

	<form action="{{ route('switch-game') }}" method="post">
		@csrf
		<div class="container">

			<div class="row my-4">
				<div class="col-12">
					<span class="h3">Настройки</span>
				</div>
			</div>


			<div class="row">
				<label class="col-12">Дата начала торгов</label>
				<div class="input-group mb-3 col-5">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="month-start">Месяц</label>
				  </div>
				  <select class="custom-select" id="month-start" name="month_start" required {{ $status ? 'disabled' : '' }}>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 1 ? 'selected' : '' }} value="1">Январь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 2 ? 'selected' : '' }} value="2">Февраль</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 3 ? 'selected' : '' }} value="3">Март</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 4 ? 'selected' : '' }} value="4">Апрель</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 5 ? 'selected' : '' }} value="5">Май</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 6 ? 'selected' : '' }} value="6">Июнь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 7 ? 'selected' : '' }} value="7">Июль</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 8 ? 'selected' : '' }} value="8">Август</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 9 ? 'selected' : '' }} value="9">Сентябрь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 10 ? 'selected' : '' }} value="10">Октябрь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 11 ? 'selected' : '' }} value="11">Ноябрь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('m') == 12 ? 'selected' : '' }} value="12">Декабрь</option>
				  </select>
				</div>

				<div class="input-group mb-3 col-5">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="year-start">Год</label>
				  </div>
				  <select class="custom-select" id="year-start" name="year_start" required {{ $status ? 'disabled' : '' }}>
				    <option disabled>Выберите год ...</option>
				    @for ($i = 1990; $i < 2030; $i++)
				    	<option {{ \App\Models\Setting::getValueByName('date_trading_start')->format('Y') == $i ? 'selected' : '' }} {{ old('year_start') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
				    @endfor
				  </select>
				</div>

			</div>


			<div class="row">
				<label class="col-12">Дата окончания торгов</label>	
				<div class="input-group mb-3 col-5">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="month-finish">Месяц</label>
				  </div>
				  <select class="custom-select" id="month-finish" name="month_finish" required {{ $status ? 'disabled' : '' }}>
				    <option disabled>Выберите месяц ...</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 1 ? 'selected' : '' }} value="1">Январь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 2 ? 'selected' : '' }} value="2">Февраль</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 3 ? 'selected' : '' }} value="3">Март</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 4 ? 'selected' : '' }} value="4">Апрель</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 5 ? 'selected' : '' }} value="5">Май</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 6 ? 'selected' : '' }} value="6">Июнь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 7 ? 'selected' : '' }} value="7">Июль</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 8 ? 'selected' : '' }} value="8">Август</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 9 ? 'selected' : '' }} value="9">Сентябрь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 10 ? 'selected' : '' }} value="10">Октябрь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 11 ? 'selected' : '' }} value="11">Ноябрь</option>
				    <option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('m') == 12 ? 'selected' : '' }} value="12">Декабрь</option>
				  </select>
				</div>

				<div class="input-group mb-3 col-5">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="year-finish">Год</label>
				  </div>
				  <select class="custom-select" id="year-finish" name="year_finish" required {{ $status ? 'disabled' : '' }}>
				    <option selected disabled>Выберите год ...</option>
				    @for ($i = 1990; $i < 2030; $i++)
				    	<option {{ \App\Models\Setting::getValueByName('date_trading_finish')->format('Y') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
				    @endfor
				  </select>
				</div>
			</div>

			<div class="row">
				<label class="col-12">Статус: {{ $status ? 'Запущен (Дата на торгах: '.$currentDate->format('m.Y').')' : 'Остановлен' }}</label>
				@php
					$period = Carbon\CarbonPeriod::create(
						\App\Models\Setting::getValueByName('current_date'),
						'1 month',
						\App\Models\Setting::getValueByName('date_trading_finish')
					);
				@endphp
				@if ($status)
					<label class="col-12">Осталось {{ $period->count() * \App\Models\Setting::getValueByName('month_in_minute') }} минут</label>
				@endif
			</div>

			<div class="row mb-3">
				<div class="col-2">
					<input class="form-control" type="number" min="1" max="30" step="1" value="{{ old('month_in_minute') ?? \App\Models\Setting::getValueByName('month_in_minute') }}" name="month_in_minute" {{ $status ? 'disabled' : '' }}>
				</div>
				<div class="col-3">
					<span>Минут за месяц</span>
				</div>
				<div class="col-6 text-left">
					@if ($status)
						<button type="submit" class="btn btn-danger">Остановить игру</button>
						<a class="btn btn-light" href="{{ route('admin.settings.index') }}"><i class="fa fa-refresh"></i></a>
					@else
						<button type="submit" class="btn btn-success">Запустить игру</button>
					@endif
				</div>
			</div>
		</div>
	</form>
	<form class="mb-4" action="{{ route('admin.users.change-password') }}" method="post">
		@csrf

		<div class="container">
			<div class="row mb-3">
				<div class="col-3">
					<label for="password">Пароль администратора</label>
	                <input id="password" class="form-control" value="{{ old('password') ?? auth()->user()->password }}" type="text" name="password" placeholder="Пароль администратора">
				</div>
				<div class="col-6">
					<label>&nbsp;</label>
					<div>
						<button type="submit" class="btn btn-primary">Изменить</button>
					</div>
				</div>
			</div>
		</div>
	</form>

@endsection