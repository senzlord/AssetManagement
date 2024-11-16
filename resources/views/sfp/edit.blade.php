@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Flex container for heading and button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Edit Perangkat SFP</h1>
    </div>

    <form action="{{ route('sfp.update', $sfp->PERANGKAT_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Save and Cancel buttons -->
        <div class="d-flex justify-content-end mb-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('sfp.index') }}" class="btn btn-danger">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <!-- Table layout for input fields -->
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>Location</strong></td>
                    <td>
                        <input type="text" class="form-control @error('LOCATION') is-invalid @enderror" name="LOCATION" value="{{ old('LOCATION', $sfp->LOCATION) }}" required>
                        @error('LOCATION')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                    <td>
                        <input type="text" class="form-control @error('VENDOR') is-invalid @enderror" name="VENDOR" value="{{ old('VENDOR', $sfp->VENDOR) }}" required>
                        @error('VENDOR')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>PRODUCT-ID DEVICE</strong></td>
                    <td>
                        <input type="text" class="form-control @error('PRODUCT_ID_DEVICE') is-invalid @enderror" name="PRODUCT_ID_DEVICE" value="{{ old('PRODUCT_ID_DEVICE', $sfp->PRODUCT_ID_DEVICE) }}" required>
                        @error('PRODUCT_ID_DEVICE')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Serial Number Device</strong></td>
                    <td>
                        <input type="text" class="form-control @error('SERIAL_NUMBER') is-invalid @enderror" name="SERIAL_NUMBER" value="{{ old('SERIAL_NUMBER', $sfp->SERIAL_NUMBER) }}" required>
                        @error('SERIAL_NUMBER')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Hostname</strong></td>
                    <td>
                        <input type="text" class="form-control @error('HOST_NAME') is-invalid @enderror" name="HOST_NAME" value="{{ old('HOST_NAME', $sfp->HOST_NAME) }}" required>
                        @error('HOST_NAME')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>IP Address</strong></td>
                    <td>
                        <input 
                            type="text" 
                            class="form-control @error('IP_ADDRESS') is-invalid @enderror" 
                            name="IP_ADDRESS" 
                            value="{{ old('IP_ADDRESS', $sfp->IP_ADDRESS) }}" 
                            pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)$"
                            title="Enter a valid IP address (e.g., 192.168.0.1)" 
                            required>
                        @error('IP_ADDRESS')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Jumlah SFP Tersedia</strong></td>
                    <td>
                        <input type="number" class="form-control @error('JUMLAH_SFP_DICABUT') is-invalid @enderror" name="JUMLAH_SFP_DICABUT" value="{{ old('JUMLAH_SFP_DICABUT', $sfp->JUMLAH_SFP_DICABUT) }}" required>
                        @error('JUMLAH_SFP_DICABUT')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
@endsection
