@extends('layouts.app')

@section('content')
    <div id="app">
        <reports-component :id="{{ $id }}"></reports-component>
    </div>
@endsection