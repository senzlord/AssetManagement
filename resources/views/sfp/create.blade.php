@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <h2 class="mb-4" style="color: #0d6efd;">Tambah Perangkat</h2>
    
    <form action="{{ route('sfp.store') }}" method="POST">
        @csrf
        
        <!-- Save and Cancel buttons -->
        <div class="d-flex justify-content-end mb-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('sfp.index') }}" class="btn btn-danger">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <!-- Table layout for input fields -->
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>No.</strong></td>
                    <td>
                        <input type="text" class="form-control" name="no" value="{{ $nextId }}" disabled>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Location</strong></td>
                    <td>
                        <input type="text" class="form-control" name="LOCATION" placeholder="Location..." value="{{ old('LOCATION') }}">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                    <td>
                        <input type="text" class="form-control" name="VENDOR" placeholder="Vendor..." value="{{ old('VENDOR') }}">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>PRODUCT-ID DEVICE</strong></td>
                    <td>
                        <input type="text" class="form-control" name="PRODUCT_ID_DEVICE" placeholder="PRODUCT-ID DEVICE..." value="{{ old('PRODUCT_ID_DEVICE') }}">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Serial Number Device</strong></td>
                    <td>
                        <input type="text" class="form-control" name="SERIAL_NUMBER" placeholder="Serial Number Device..." value="{{ old('SERIAL_NUMBER') }}">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Hostname</strong></td>
                    <td>
                        <input type="text" class="form-control" name="HOST_NAME" placeholder="Hostname..." value="{{ old('HOST_NAME') }}">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>IP Address</strong></td>
                    <td>
                        <input type="text" class="form-control" name="IP_ADDRESS" placeholder="IP Address..." value="{{ old('IP_ADDRESS') }}">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Jumlah SFP Dicabut</strong></td>
                    <td>
                        <input type="number" class="form-control" name="JUMLAH_SFP_DICABUT" placeholder="Jumlah SFP Dicabut..." value="{{ old('JUMLAH_SFP_DICABUT') }}">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
@endsection