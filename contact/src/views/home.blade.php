@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <button class="btn btn-primary"><a href="/links/" class="text-white">Links</a></button>
                    <button class="btn btn-success"><a href="/links/create" class="text-white">Link Create</a></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
