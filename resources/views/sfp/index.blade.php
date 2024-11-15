@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('sfp.index') }}" method="GET" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Hostname/Brand/Serial Number..." name="search" value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
        <div>
            @can('add device data')
                <a href="{{ route('sfp.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus"></i> Perangkat
                </a>
            @endcan
            <button class="btn btn-primary" onclick="window.location='{{ route('sfp.export') }}'">Export ke Excel</button>
        </div>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Location</th>
                <th>PRODUCT-ID DEVICE</th>
                <th>Serial Number Device</th>
                <th>Hostname</th>
                <th>IP Address</th>
                <th>Jumlah SFP Dicabut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sfps as $index => $sfp)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sfp->LOCATION }}</td>
                <td>{{ $sfp->PRODUCT_ID_DEVICE }}</td>
                <td>{{ $sfp->SERIAL_NUMBER }}</td>
                <td>{{ $sfp->HOST_NAME }}</td>
                <td>{{ $sfp->IP_ADDRESS }}</td>
                <td>{{ $sfp->JUMLAH_SFP_DICABUT }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $sfps->links() }}
    </div>
</div>
@endsection