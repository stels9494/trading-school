@extends('layouts.admin')

@section('content')
	<div class="container">


		<div class="row my-4">
			<div class="col-12">
				<span class="h3">Команды</span>
			</div>
		</div>


		<form class="mb-4" action="{{ route('admin.commands.store') }}" method="post">
			@csrf
			<div class="row mb-3">
				<div class="col-3">
					<label for="name">Название</label>
                    <input id="name" class="form-control" value="{{ old('name') }}" type="text" name="name" placeholder="Название команды">
				</div>
				<div class="col-3">
					<label for="balance">Баланс</label>
					<input id="balance" class="form-control" value="{{ old('balance') ?? 0 }}" type="number" name="balance" placeholder="Баланс" min="0" max="999999999" step="1">
				</div>
				<div class="col-6">
					<label>&nbsp;</label>
					<div>
						<button type="submit" class="btn btn-success">Добавить команду</button>
					</div>
				</div>
			</div>
		</form>

		<div class="row">
			<div class="col-12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">Название</th>
							<th scope="col">Баланс</th>
							<th scope="col">Игроков</th>
							<th scope="col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data['commands'] as $command)
							<tr>
								<td>{{ $command->name }}</td>
								<td>{{ $command->balance }}</td>
								<td>{{ $command->users()->count() }}</td>
								<td class="text-right">
									<a href="{{ route('admin.commands.show', $command) }}" class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i></a>
									<form style="display: inline-block;" action="{{ route('admin.commands.destroy', $command) }}" method="post" onsubmit="return confirm('Удалить команду со всей историей торговли и участниками?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
									</form>
								</td>
							</tr>
						@endforeach

						@if (!$data['commands']->count())
							<tr>
								<td colspan="4" class="text-center">
									<span class="h6 ">Команд нет</span>
								</td>
							</tr>
						@endif
					</tbody>
				</table>	
			</div>
		</div>
	</div>
@endsection