@extends('layouts.admin')

@section('content')
    <script src="{{ mix('js/app.js') }}" defer></script>
    <div id="app">
        <admin-live
            :commands="{{ \App\Models\Command::get() }}"
        ></admin-live>
    </div>
@endsection
