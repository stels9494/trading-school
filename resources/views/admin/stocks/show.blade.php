@extends('layouts.admin')

@push('scripts')
	<script>
		$(document).ready(function () {
			$('.import').click(function () {
				$('#file_quotations').click();
			});

			$('#file_quotations').change(function () {
				$(this).parent('form').submit();
			});
		});
	</script>
@endpush

@section('content')

	
	<form class="d-none" action="{{ route('admin.stocks.import-quotations-for-stock', $data['stock']) }}" method="post" enctype="multipart/form-data">
		@csrf
		<input id="file_quotations" type="file" name="file_quotations">
	</form>



	<div class="container">


		{{-- хлебные крошки --}}
		<div class="row my-4">
			<div class="col-12">
				<a class="h3" href="{{ route('admin.stocks.index') }}">Акции</a>
				<img style="margin-top: -7px;" class="mx-3" src="/img/arrow-right.svg">
				<span class="h3">{{ $data['stock']->name }}</span>
			</div>
		</div>

		{{-- данные акции --}}
		<form class="mb-4" action="{{ route('admin.stocks.update', $data['stock']) }}" method="post">
			@csrf
			@method('patch')
			<div class="row mb-3">
				<div class="col-12">
					<h5>Данные акции</h5>
				</div>	
				<div class="col-3">
					<label for="name">Название</label>
                    <input id="name" class="form-control" value="{{ $data['stock']->name }}" type="text" name="name" placeholder="Название акции">
				</div>
				<div class="col-6">
					<label>&nbsp;</label>
					<div>
					    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Сохранить</button>
					    <button class="btn btn-secondary import" type="button" onclick=""><i class="fa fa-upload"></i> Импорт</button>
					    <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Назад</a>
					</div>
				</div>
			</div>
		</form>

		{{-- импорт котировок --}}


		{{-- очистить котировки --}}

		{{-- график --}}

	</div>
@endsection