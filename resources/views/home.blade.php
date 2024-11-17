@extends('layouts.sidebar-layout')

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Card for Hardware -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Hardware</h5>
                    <p class="card-text display-4">{{ $Hardware }}</p>
                </div>
                <div class="card-footer">
                    <small>Hardware assets in your inventory</small>
                </div>
            </div>
        </div>

        <!-- Card for Non-Hardware -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Non-Hardware</h5>
                    <p class="card-text display-4">{{ $NonHardware }}</p>
                </div>
                <div class="card-footer">
                    <small>Non-Hardware assets in your inventory</small>
                </div>
            </div>
        </div>

        <!-- Card for SFP -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Total SFP</h5>
                    <p class="card-text display-4">{{ $SFP }}</p>
                </div>
                <div class="card-footer">
                    <small>SFP assets in your inventory</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
