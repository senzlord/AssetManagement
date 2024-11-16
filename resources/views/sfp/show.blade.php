@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Flex container for heading and button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Detail Perangkat SFP</h1>
        @can('edit device data')
            <a href="{{ route('sfp.edit', $sfp->PERANGKAT_ID) }}" class="btn btn-success me-2">
                Edit Perangkat
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">
            Detail Information of Device
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 25%; background-color: #f8f9fa;"><strong>Location</strong></td>
                        <td>{{ $sfp->LOCATION }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                        <td>{{ $sfp->VENDOR }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa;"><strong>PRODUCT-ID DEVICE</strong></td>
                        <td>{{ $sfp->PRODUCT_ID_DEVICE }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa;"><strong>Serial Number Device</strong></td>
                        <td>{{ $sfp->SERIAL_NUMBER }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa;"><strong>Hostname</strong></td>
                        <td>{{ $sfp->HOST_NAME }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa;"><strong>IP Address</strong></td>
                        <td>{{ $sfp->IP_ADDRESS }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa;"><strong>Jumlah SFP Dicabut</strong></td>
                        <td>{{ $sfp->JUMLAH_SFP_DICABUT }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('sfp.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection