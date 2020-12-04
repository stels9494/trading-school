@extends('layouts.admin')

@push('stylesheets')
	<style>
		.body {
			height: 200px;
			padding-top: 40px;
		}
	</style>
@endpush

@section('content')
	<div class="container">
		<div class="row my-4">
			<div class="col-12">
				<span class="h3">Общая информация</span>
			</div>
		</div>

		<div class="row">
	        <div class="col-lg-4 mb-4">
	            <div class="m-10 card p-30">
	            	<div class="body">
		            	<h5 class="title text-center font-weight-bold" style="color: #46403e;">Кол-во команд</h5>
		            	<div class="value text-center" style="font-size: 40px;">{{ $data['commandsCount'] }}</div>
	            	</div>
	            </div>
	        </div>

	        <div class="col-lg-4 mb-4">
	            <div class="m-10 card p-30">
	            	<div class="body">
		            	<h5 class="title text-center font-weight-bold" style="color: #46403e;">Кол-во участников</h5>
		            	<div class="value text-center" style="font-size: 40px;">{{ $data['usersCount'] }}</div>
	            	</div>
	            </div>
	        </div>

	        <div class="col-lg-4 mb-4">
	            <div class="m-10 card p-30">
	            	<div class="body">
		            	<h5 class="title text-center font-weight-bold" style="color: #46403e;">Кол-во акций</h5>
		            	<div class="value text-center" style="font-size: 40px;">{{ $data['stocksCount'] }}</div>
	            	</div>
	            </div>
	        </div>

	        <div class="col-lg-4 mb-4">
	            <div class="m-10 card p-30">
	            	<div class="body">
		            	<h5 class="title text-center font-weight-bold" style="color: #46403e;">Кол-во сделок</h5>
		            	<div class="value text-center" style="font-size: 40px;">{{ $data['tradesCount'] }}</div>
	            	</div>
	            </div>
	        </div>

	        <div class="col-lg-4 mb-4">
	            <div class="m-10 card p-30">
	            	<div class="body">
		            	<h5 class="title text-center font-weight-bold" style="color: #46403e;">Кол-во активных акций</h5>
		            	<div class="value text-center" style="font-size: 40px;">{{ $data['stocksOnExchangeCount'] }}</div>
	            	</div>
	            </div>
	        </div>

	        <div class="col-lg-4 mb-4">
	            <div class="m-10 card p-30">
	            	<div class="body">
		            	<h5 class="title text-center font-weight-bold" style="color: #46403e;">Кол-во акций без котировок</h5>
		            	<div class="value text-center" style="font-size: 40px;">{{ $data['stocksWithoutQuotations'] }}</div>
	            	</div>
	            </div>
	        </div>
	        {{-- кол-во сделок --}}
	        {{-- кол-во активных акций --}}
	        {{-- кол-во акций без котировок --}}
	    </div>

	</div>
@endsection