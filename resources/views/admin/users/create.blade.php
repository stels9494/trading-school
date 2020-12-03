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
				<span class="h3">Создание пользователя</span>
			</div>
		</div>
		<form action="{{ route('admin.users.store', $data['command']) }}" method="post">
			@csrf
			<div class="row">
				<div class="col-4">
					<div class="form-group">
						<label for="lastname">Фамилия</label>
						<input type="text" class="form-control" id="lastname" aria-describedby="lastname" placeholder="Фамилия" name="lastname">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="firstname">Имя</label>
						<input type="text" class="form-control" id="firstname" aria-describedby="firstname" placeholder="Имя" name="firstname">
					</div>	
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="patronymic">Отчество</label>
						<input type="text" class="form-control" id="patronymic" aria-describedby="patronymic" placeholder="Отчество" name="patronymic">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="login">Логин</label>
						<input type="text" class="form-control" id="login" aria-describedby="login" placeholder="Логин" name="login">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="password">Пароль</label>
						<input type="text" class="form-control" id="password" aria-describedby="password" placeholder="Пароль" name="password">
					</div>
				</div>
				<div class="col-12">
					<button class="btn btn-success" type="submit">Создать</button>
					<a class="btn btn-secondary" href="{{ route('admin.commands.show', $data['command']) }}">Назад</a>
				</div>
			</div>
		</form>



	</div>
@endsection