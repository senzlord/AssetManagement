@extends('layouts.sidebar-layout')

@section('content')
<div class="container text-center">
    <h1 class="display-3">403</h1>
    <p class="lead">You are not authorized to access this page.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
</div>
@endsection