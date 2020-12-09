@extends('layouts.admin')

@push('scripts')
	<script>
		$(document).ready(function () {
			$('.commander-switch').change(function () {
				var state = $(this).parent().find('input').is(':checked');
				console.log(state);
				var userId = $(this).parent().find('input').data('userId');
				var url = $(this).parent().find('input').data('switchUrl');

				$.ajax({
					type: 'post',
					url: url,
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
					data: {
						commander: state ? 1 : 0
					},
					success: function (data) {
						console.log(data)
					},
					fail: function () {
						alert('Произошла ошибка');
					}
				});
			});
		})
	</script>
@endpush

@section('content')
	<div class="container">

		{{-- хлебные крошки --}}
		<div class="row my-4">
			<div class="col-12">
				<a class="h3" href="{{ route('admin.commands.index') }}">Команды</a>
				<img style="margin-top: -7px;" class="mx-3" src="/img/arrow-right.svg">
				<span class="h3">{{ $data['command']->name }}</span>
			</div>
		</div>

		{{-- редактирование данных команды --}}
		<form class="mb-4" action="{{ route('admin.commands.update', $data['command']) }}" method="post">
			@csrf
			@method('patch')
			<div class="row mb-3">
				<div class="col-12">
					<h5>Данные команды</h5>
				</div>	
				<div class="col-3">
					<label for="name">Название</label>
                    <input id="name" class="form-control" value="{{ $data['command']->name }}" type="text" name="name" placeholder="Название команды">
				</div>
				<div class="col-3">
					<label for="balance">Баланс</label>
					<input id="balance" class="form-control" value="{{ $data['command']->balance }}" type="number" name="balance" placeholder="Баланс" min="0" max="9999999999999.99" step="0.01">
				</div>
				<div class="col-6">
					<label>&nbsp;</label>
					<div>
					    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  Сохранить</button>
					    <a href="{{ route('admin.commands.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Назад</a>						
					</div>
				</div>
			</div>
		</form>

		{{-- список пользователей --}}
		<div class="row">
			<div class="col-12">
				<h5 class="my-3">Участники <a class="ml-3 btn btn-sm btn-success" href="{{ route('admin.users.create', $data['command']) }}">Добавить</a></h5>
				<table class="table table-striped">
					<thead>
						<tr>
							<th col="col">Логин</th>
							<th class="text-center" scope="col">Фамилия</th>
							<th class="text-center" scope="col">Имя</th>
							<th class="text-center" scope="col">Отчество</th>
							<th class="text-center" scope="col">Командир</th>
							<th scope="col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data['command']->users as $user)
							<tr>
								<td>{{ $user->login }}</td>
								<td class="text-center">{{ $user->firstname }}</td>
								<td class="text-center">{{ $user->lastname }}</td>
								<td class="text-center">{{ $user->patronymic }}</td>
								<td class="text-center">
									
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input commander-switch" id="commander{{ $user->id }}" data-user-id="{{ $user->id }}" data-switch-url="{{ route('admin.users.set-commander', [$data['command'], $user]) }}" {{ $user->commander ? 'checked' : '' }}>
										<label class="custom-control-label" for="commander{{ $user->id }}"></label>
									</div>

								</td>
								<td class="text-right">
									<a href="{{ route('admin.users.edit', [$data["command"], $user]) }}" class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i></a>
									<form style="display: inline-block;" action="{{ route('admin.users.destroy', [$data["command"], $user]) }}" method="post" onsubmit="return confirm('Удалить пользователя?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
									</form>
								</td>
							</tr>
						@endforeach

						@if (!$data['command']->users->count())
							<tr>
								<td colspan="6" class="text-center">
									<span class="h6 ">Участников нет</span>
								</td>
							</tr>
						@endif
					</tbody>
				</table>	
			</div>
		</div>

		{{-- портфель команды --}}
		<div class="row mt-4">
			<div class="col-12">
				<h5 class="my-3">
					Портфель команды
				</h5>
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">Название акции</th>
							<th class="text-center" scope="col">Кол-во</th>
						</tr>
					</thead>
					<tbody>

						@foreach ($data['command']->stocks as $stock)
							<tr>
								<td>{{ $stock->stock->name }}</td>
								<td class="text-center">{{ $stock->count }}</td>
							</tr>
						@endforeach

						@if (!$data['command']->stocks->count())
							<tr>
								<td colspan="5" class="text-center">
									<span class="h6 ">Портфель пустой</span>
								</td>
							</tr>
						@endif

					</tbody>
				</table>
			</div>

		</div>

		{{-- история торгов команды --}}
		<div class="row my-4">
			<div class="col-12">
				<h5 class="my-3">
					История торгов 
					<form class="d-inline-block" action="{{ route('admin.commands.clear-histories', $data['command']) }}" method="post" onsubmit="return confirm('Очистить историю торгов текущей команды?');">
						@csrf
						<button class="ml-3 btn btn-sm btn-danger">Очистить</button>
					</form>
				</h5>
				<table class="table table-striped">
					<thead>
						<tr>
							<th col="col">Время по бирже</th>
							<th class="text-center" scope="col">Название</th>
							<th class="text-center" scope="col">Кол-во</th>
							<th class="text-center" scope="col">Цена</th>
							<th class="text-center" scope="col">Действие</th>
						</tr>
					</thead>
					<tbody>

						@foreach ($data['command']->tradingHistories()->orderBy('id', 'desc')->get() as $history)
							<tr>
								<td>{{ $history->time_by_exchange->format('m.Y') }}</td>
								<td class="text-center">{{ $history->stock->name }}</td>
								<td class="text-center">{{ abs($history->count) }}</td>
								<td class="text-center">{{ $history->price }}</td>
								<td class="text-center">{{ $history->action }}</td>
							</tr>
						@endforeach

						@if (!$data['command']->tradingHistories->count())
							<tr>
								<td colspan="5" class="text-center">
									<span class="h6 ">Истории нет</span>
								</td>
							</tr>
						@endif


					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection