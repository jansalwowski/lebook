@extends('layouts.app')

@section('content')
    <profile :user="{{ $profile }}"></profile>
@stop