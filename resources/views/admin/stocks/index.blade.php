@extends('layouts.admin')

@push('scripts')
	<script>
		$(document).ready(function () {
			$('.exchange-switch').change(function () {
				var state = $(this).parent().find('input').is(':checked');
				var stockId = $(this).parent().find('input').data('stockId');
				var url = $(this).parent().find('input').data('switchUrl');

				$.ajax({
					type: 'post',
					url: url,
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
					data: {
						on_the_exchange: state ? 1 : 0
					},
					success: function (data) {
						console.log(data)
					},
					fail: function () {
						alert('Произошла ошибка');
					}
				});
			});

			// действие на клик кнопки импорт
			$('.import').click(function () {
				$('#file_quotations').click();
			});

			// действие на изменение файлового инпута
			$('#file_quotations').change(function () {
				$(this).parent('form').submit();
			});
		})
	</script>
@endpush

@section('content')

	{{-- форма для импорта котировок и создания акции --}}
	<form class="d-none" action="{{ route('admin.stocks.import-quotations') }}" method="post" enctype="multipart/form-data">
		@csrf
		<input id="file_quotations" type="file" name="file_quotations">
	</form>

	<div class="container">
		<div class="row my-4">
			<div class="col-12">
				<span class="h3">Акции</span>
			</div>
		</div>

		<form class="mb-4" action="{{ route('admin.stocks.store') }}" method="post">
			@csrf
			<div class="row mb-3">
				<div class="col-3">
					<label for="name">Название</label>
	                <input id="name" class="form-control" value="{{ old('name') }}" type="text" name="name" placeholder="Название акции">
				</div>
				<div class="col-6">
					<label>&nbsp;</label>
					<div>
						<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Добавить</button>
						<button class="btn btn-secondary import" type="button"><i class="fa fa-upload"></i> Импорт</button>
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
							<th class="text-center" scope="col">Есть котировки</th>
							<th class="text-center" scope="col">Торгуется на бирже</th>
							<th scope="col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data['stocks'] as $stock)
							<tr>
								<td>{{ $stock->name }}</td>
								<td class="text-center">{{ $stock->isQuotations ? 'да' : 'нет' }}</td>
								<td class="text-center">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input exchange-switch" id="on_the_exchange{{ $stock->id }}" data-stock-id="{{ $stock->id }}" data-switch-url="{{ route('admin.stocks.set-exchange', $stock) }}" {{ $stock->on_the_exchange ? 'checked' : '' }}>
										<label class="custom-control-label" for="on_the_exchange{{ $stock->id }}"></label>
									</div>
								</td>

								<td class="text-right">
									<a href="{{ route('admin.stocks.show', $stock) }}" class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i></a>
									<form style="display: inline-block;" action="{{ route('admin.stocks.destroy', $stock) }}" method="post" onsubmit="return confirm('Удалить акцию со всей связанной историей торговли?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
									</form>
								</td>

							</tr>
						@endforeach

						@if (!$data['stocks']->count())
							<tr>
								<td colspan="4" class="text-center">
									<span class="h6 ">Акций нет</span>
								</td>
							</tr>
						@endif
					</tbody>
				</table>	
			</div>
		</div>

	</div>


@endsection