@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Flex container for heading and button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Perangkat SFP</h1>
        <div>
            <div>
                @can('add device data')
                    <a href="{{ route('sfp.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus"></i> Perangkat
                    </a>
                @endcan
                @can('generate reports')
                <button class="btn btn-primary" onclick="window.location='{{ route('sfp.export') }}'">Export ke Excel</button>
                @endcan
            </div>
        </div>
    </div>

    @can('search device data')
    <div class="mb-3">
        <form action="{{ route('sfp.index') }}" method="GET" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Hostname/Brand/Serial Number..." name="search" value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
    @endcan

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Location</th>
                <th>PRODUCT-ID DEVICE</th>
                <th>Serial Number Device</th>
                <th>Hostname</th>
                <th>IP Address</th>
                <th>Jumlah SFP Tersedia</th>
                <th>Action</th>
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
                <td>
                    <a href="{{ route('sfp.show', $sfp->PERANGKAT_ID) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $sfps->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection