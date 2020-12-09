@extends('layouts.admin')

@push('scripts')
	<script>
		$(document).ready(function () {
			$('#month_in_minute').change(function () {
				$('#continue-game').attr('href', $('#continue-game').data('href') + '?month_in_minute=' + $(this).val());
			})
		});
	</script>
@endpush

@section('content')

	@php
		use App\Models\Setting;
		use App\Models\StockQuotation;
		$status = Setting::getValueByName('status');
		$pause = Setting::getValueByName('is_pause');
		$currentDate = Setting::getValueByName('current_date');

		$monthСurrentDate = $currentDate->format('m');
		$yearСurrentDate = $currentDate->format('Y');

		$quotationsCount = StockQuotation::count();

		$monthStart = Setting::getValueByName('date_trading_start')->format('m');
		$monthFinish = Setting::getValueByName('date_trading_finish')->format('m');
		$yearStart = Setting::getValueByName('date_trading_start')->format('Y');
		$yearFinish = Setting::getValueByName('date_trading_finish')->format('Y');

	@endphp

	<div class="container">
		<div class="row my-4">
			<div class="col-12">
				<span class="h3">Настройки</span>
			</div>
		</div>
	</div>
    <form action="{{ route('set-current') }}" method="post">
        @csrf
        <div class="container">
            <div class="row">
                <label class="col-12">Текущая дата (дата до которой игроки видят котировки)</label>
                <div class="input-group mb-3 col-5">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="month-current">Месяц</label>
                    </div>
                    <select class="custom-select" id="month-current" name="month_current" required {{ $status ? 'disabled' : '' }}>
                        <option disabled>Выберите месяц ...</option>
                        <option {{ $monthСurrentDate == 1 ? 'selected' : '' }} value="1">Январь</option>
                        <option {{ $monthСurrentDate == 2 ? 'selected' : '' }} value="2">Февраль</option>
                        <option {{ $monthСurrentDate == 3 ? 'selected' : '' }} value="3">Март</option>
                        <option {{ $monthСurrentDate == 4 ? 'selected' : '' }} value="4">Апрель</option>
                        <option {{ $monthСurrentDate == 5 ? 'selected' : '' }} value="5">Май</option>
                        <option {{ $monthСurrentDate == 6 ? 'selected' : '' }} value="6">Июнь</option>
                        <option {{ $monthСurrentDate == 7 ? 'selected' : '' }} value="7">Июль</option>
                        <option {{ $monthСurrentDate == 8 ? 'selected' : '' }} value="8">Август</option>
                        <option {{ $monthСurrentDate == 9 ? 'selected' : '' }} value="9">Сентябрь</option>
                        <option {{ $monthСurrentDate == 10 ? 'selected' : '' }} value="10">Октябрь</option>
                        <option {{ $monthСurrentDate == 11 ? 'selected' : '' }} value="11">Ноябрь</option>
                        <option {{ $monthСurrentDate == 12 ? 'selected' : '' }} value="12">Декабрь</option>
                    </select>
                </div>

                <div class="input-group mb-3 col-5">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="year-current">Год</label>
                    </div>
                    <select class="custom-select" id="year-current" name="year_current" required {{ $status ? 'disabled' : '' }}>
                        <option disabled>Выберите год ...</option>
                        @for ($i = StockQuotation::getMinYear(); $i <= StockQuotation::getMaxYear(); $i++)
                            <option {{ $yearСurrentDate == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                @if (!$status)
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                @endif
            </div>
        </div>
    </form>
    <hr>
	@if ($quotationsCount)
		<form action="{{ route('switch-game') }}" method="post">
			@csrf
			<div class="container">
				<div class="row">
					<label class="col-12">Дата начала торгов</label>
					<div class="input-group mb-3 col-5">
					  <div class="input-group-prepend">
					    <label class="input-group-text" for="month-start">Месяц</label>
					  </div>
					  <select class="custom-select" id="month-start" name="month_start" required {{ $status ? 'disabled' : '' }}>
					    <option {{ $monthStart == 1 ? 'selected' : '' }} value="1">Январь</option>
					    <option {{ $monthStart == 2 ? 'selected' : '' }} value="2">Февраль</option>
					    <option {{ $monthStart == 3 ? 'selected' : '' }} value="3">Март</option>
					    <option {{ $monthStart == 4 ? 'selected' : '' }} value="4">Апрель</option>
					    <option {{ $monthStart == 5 ? 'selected' : '' }} value="5">Май</option>
					    <option {{ $monthStart == 6 ? 'selected' : '' }} value="6">Июнь</option>
					    <option {{ $monthStart == 7 ? 'selected' : '' }} value="7">Июль</option>
					    <option {{ $monthStart == 8 ? 'selected' : '' }} value="8">Август</option>
					    <option {{ $monthStart == 9 ? 'selected' : '' }} value="9">Сентябрь</option>
					    <option {{ $monthStart == 10 ? 'selected' : '' }} value="10">Октябрь</option>
					    <option {{ $monthStart == 11 ? 'selected' : '' }} value="11">Ноябрь</option>
					    <option {{ $monthStart == 12 ? 'selected' : '' }} value="12">Декабрь</option>
					  </select>
					</div>

					<div class="input-group mb-3 col-5">
					  <div class="input-group-prepend">
					    <label class="input-group-text" for="year-start">Год</label>
					  </div>
					  <select class="custom-select" id="year-start" name="year_start" required {{ $status ? 'disabled' : '' }}>
					    <option disabled>Выберите год ...</option>
					    @for ($i = StockQuotation::getMinYear(); $i <= StockQuotation::getMaxYear(); $i++)
					    	<option {{ $yearStart == $i ? 'selected' : '' }} {{ old('year_start') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
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
					    <option {{ $monthFinish == 1 ? 'selected' : '' }} value="1">Январь</option>
					    <option {{ $monthFinish == 2 ? 'selected' : '' }} value="2">Февраль</option>
					    <option {{ $monthFinish == 3 ? 'selected' : '' }} value="3">Март</option>
					    <option {{ $monthFinish == 4 ? 'selected' : '' }} value="4">Апрель</option>
					    <option {{ $monthFinish == 5 ? 'selected' : '' }} value="5">Май</option>
					    <option {{ $monthFinish == 6 ? 'selected' : '' }} value="6">Июнь</option>
					    <option {{ $monthFinish == 7 ? 'selected' : '' }} value="7">Июль</option>
					    <option {{ $monthFinish == 8 ? 'selected' : '' }} value="8">Август</option>
					    <option {{ $monthFinish == 9 ? 'selected' : '' }} value="9">Сентябрь</option>
					    <option {{ $monthFinish == 10 ? 'selected' : '' }} value="10">Октябрь</option>
					    <option {{ $monthFinish == 11 ? 'selected' : '' }} value="11">Ноябрь</option>
					    <option {{ $monthFinish == 12 ? 'selected' : '' }} value="12">Декабрь</option>
					  </select>
					</div>

					<div class="input-group mb-3 col-5">
					  <div class="input-group-prepend">
					    <label class="input-group-text" for="year-finish">Год</label>
					  </div>
					  <select class="custom-select" id="year-finish" name="year_finish" required {{ $status ? 'disabled' : '' }}>
					    <option disabled>Выберите год ...</option>
					    @for ($i = StockQuotation::getMinYear(); $i <= StockQuotation::getMaxYear(); $i++)
					    	<option {{ $yearFinish == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
					    @endfor
					  </select>
					</div>
				</div>

				<div class="row">
					<label class="col-12">Статус: {{ $status ? 'Запущен (Дата на торгах: '.$currentDate->format('m.Y').')' : 'Остановлен' }}</label>
					@php
						$period = Carbon\CarbonPeriod::create(
							Setting::getValueByName('current_date'),
							'1 month',
							Setting::getValueByName('date_trading_finish')
						);
					@endphp
					@if ($status)
						<label class="col-12">Осталось {{ $period->count() * Setting::getValueByName('month_in_minute') }} минут</label>
					@endif
				</div>

				<div class="row mb-3">
					<div class="col-2">
						<input id="month_in_minute" class="form-control" type="number" min="1" max="30" step="1" value="{{ old('month_in_minute') ?? Setting::getValueByName('month_in_minute') }}" name="month_in_minute" {{ $status && !$pause ? 'disabled' : '' }}>
					</div>
					<div class="col-3">
						<span>Минут за месяц</span>
					</div>
					<div class="col-6 text-left">
						@if ($status)
							<button type="submit" class="btn btn-danger">Остановить игру</button>
	                        @if (Setting::getValueByName('is_pause'))
	                            <a id="continue-game" href="{{ route('admin.switch-pause') }}" class="mr-2 btn btn-light" data-href="{{ route('admin.switch-pause') }}">Продолжить</a>
	                        @else
	                            <a href="{{ route('admin.switch-pause') }}" class="mr-2 btn btn-light">Поставить на паузу</a>
	                        @endif
							<a class="btn btn-light" href="{{ route('admin.settings.index') }}"><i class="fa fa-refresh"></i></a>
						@else
							<button type="submit" class="btn btn-success">Запустить игру</button>
						@endif
					</div>
				</div>
			</div>
		</form>
	@else
		<h3 class="text-center">Для игры нужны котировки</h3>
	@endif

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
