@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-sm-6">
            @include('auth.registerPanel')
        </div>

        <div class="col-sm-6">
            @include('auth.loginPanel')
        </div>

    </div>
</div>
@endsection
