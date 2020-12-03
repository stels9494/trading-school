@extends('layouts.admin')

@section('content')
	<div class="container">
		{{-- хлебные крошки --}}
		<div class="row my-4">
			<div class="col-12">
				<a class="h3" href="{{ route('admin.commands.index') }}">Команды</a>
				<img style="margin-top: -7px;" class="mx-3" src="/img/arrow-right.svg">
				<a class="h3" href="{{ route('admin.commands.show', $data['command']) }}">{{ $data['command']->name }}</a>
				<img style="margin-top: -7px;" class="mx-3" src="/img/arrow-right.svg">
				<span class="h3">Редактирование пользователя - {{ $data['user']->login }}</span>
			</div>
		</div>
		<form action="{{ route('admin.users.update', [$data['command'], $data['user']]) }}" method="post">
			@csrf
			@method('patch')
			<div class="row">
				<div class="col-4">
					<div class="form-group">
						<label for="lastname">Фамилия</label>
						<input type="text" class="form-control" id="lastname" aria-describedby="lastname" placeholder="Фамилия" name="lastname" value="{{ old('lastname') ?? $data['user']->lastname }}">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="firstname">Имя</label>
						<input type="text" class="form-control" id="firstname" aria-describedby="firstname" placeholder="Имя" name="firstname" value="{{ old('firstname') ?? $data['user']->firstname }}">
					</div>	
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="patronymic">Отчество</label>
						<input type="text" class="form-control" id="patronymic" aria-describedby="patronymic" placeholder="Отчество" name="patronymic" value="{{ old('patronymic') ?? $data['user']->patronymic }}">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="login">Логин</label>
						<input type="text" class="form-control" id="login" aria-describedby="login" placeholder="Логин" name="login" value="{{ old('login') ?? $data['user']->login }}">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="password">Пароль</label>
						<input type="text" class="form-control" id="password" aria-describedby="password" placeholder="Пароль" name="password" value="{{ old('password') ?? $data['user']->password }}">
					</div>
				</div>
				<div class="col-12">
					<button class="btn btn-success" type="submit">Сохранить</button>
					<a class="btn btn-secondary" href="{{ route('admin.commands.show', $data['command']) }}">Назад</a>
				</div>
			</div>
		</form>



	</div>
@endsection