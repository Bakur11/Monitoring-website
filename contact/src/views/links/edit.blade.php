@extends('layouts.app')

@section('content')
    <div id="app">
        <link-edit-component :id="{{ $id }}"></link-edit-component>
    </div>
@endsection