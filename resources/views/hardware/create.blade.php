@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Heading -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Tambah Perangkat Hardware</h1>
    </div>

    <form action="{{ route('hardware.store') }}" method="POST">
        @csrf

        <!-- Save and Cancel buttons -->
        <div class="d-flex justify-content-end mb-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('hardware.index') }}" class="btn btn-danger">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>No.</strong></td>
                    <td>
                        <input type="text" class="form-control" value="{{ $nextId }}" disabled>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Hostname</strong></td>
                    <td>
                        <input type="text" class="form-control" name="HOST_NAME" placeholder="Hostname..." value="{{ old('HOST_NAME') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Brand</strong></td>
                    <td>
                        <input type="text" class="form-control" name="BRAND" placeholder="Brand..." value="{{ old('BRAND') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Kategori</strong></td>
                    <td>
                        <select class="form-control" name="CATEGORY" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ old('CATEGORY') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Serial Number</strong></td>
                    <td>
                        <input type="text" class="form-control" name="SERIAL_NUMBER" placeholder="Serial Number..." value="{{ old('SERIAL_NUMBER') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>IP Address</strong></td>
                    <td>
                        <input 
                            type="text" 
                            class="form-control @error('IP_ADDRESS') is-invalid @enderror" 
                            name="IP_ADDRESS" 
                            value="{{ old('IP_ADDRESS') }}" 
                            pattern="^((25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)$"
                            title="Enter a valid IP address (e.g., 192.168.0.1)" 
                            required>
                        @error('IP_ADDRESS')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Lokasi Perangkat</strong></td>
                    <td>
                        <input type="text" class="form-control" name="LOCATION" placeholder="Lokasi Perangkat..." value="{{ old('LOCATION') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>PIC</strong></td>
                    <td>
                        <input type="text" class="form-control" name="USER" placeholder="User..." value="{{ old('USER') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                    <td>
                        <input type="text" class="form-control" name="VENDOR" placeholder="Vendor..." value="{{ old('VENDOR') }}" required>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <!-- Hardware Section -->
        <h4 class="mt-4">Hardware</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>End-of-Support</strong></td>
                    <td>
                        <input type="date" class="form-control" name="EOS_HARDWARE" id="eos_hardware">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                    <td>
                        <input type="text" class="form-control" id="hardware_time_left" placeholder="Calculated automatically..." disabled>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <!-- Firmware / OS Section -->
        <h4 class="mt-4">Firmware / OS</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>Version</strong></td>
                    <td>
                        <input type="text" class="form-control" name="FIRMWARE" placeholder="Version..." value="{{ old('FIRMWARE') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>End-of-Support</strong></td>
                    <td>
                        <input type="date" class="form-control" name="EOS_FIRMWARE" id="eos_firmware">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                    <td>
                        <input type="text" class="form-control" id="firmware_time_left" placeholder="Calculated automatically..." disabled>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <!-- Licenses Section -->
        <h4 class="mt-4">Licenses</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>End Date</strong></td>
                    <td>
                        <input type="date" class="form-control" name="LICENCE_END_DATE" id="licence_end_date">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                    <td>
                        <input type="text" class="form-control" id="license_time_left" placeholder="Calculated automatically..." disabled>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <!-- Kontrak / ATS Section -->
        <h4 class="mt-4">Kontrak / ATS</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>Nama Kontrak</strong></td>
                    <td>
                        <input type="text" class="form-control" name="NAMA_KONTRAK" placeholder="Nama..." value="{{ old('NAMA_KONTRAK') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>No Kontrak</strong></td>
                    <td>
                        <input type="text" class="form-control" name="NO_KONTRAK" placeholder="No..." value="{{ old('NO_KONTRAK') }}" required>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Status Support</strong></td>
                    <td>
                        <select class="form-control" name="STATUS_SUPPORT" required>
                            <option value="">Pilih Status Support</option>
                            <option value="Support" {{ old('STATUS_SUPPORT') == 'Support' ? 'selected' : '' }}>Support</option>
                            <option value="Tidak Support" {{ old('STATUS_SUPPORT') == 'Tidak Support' ? 'selected' : '' }}>Tidak Support</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>End Date</strong></td>
                    <td>
                        <input type="date" class="form-control" name="ATS_END_DATE" id="ats_end_date">
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                    <td>
                        <input type="text" class="form-control" id="ats_time_left" placeholder="Calculated automatically..." disabled>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>PIC</strong></td>
                    <td>
                        <input type="text" class="form-control" name="PIC" placeholder="PIC..." value="{{ old('PIC') }}" required>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
@endsection

@section('script')
<script>
    function calculateTimeLeft(inputId, outputId) {
        const inputDate = document.getElementById(inputId).value;
        if (inputDate) {
            const endDate = moment(inputDate);
            const currentDate = moment();
            const daysLeft = endDate.diff(currentDate, 'days');
            document.getElementById(outputId).value = daysLeft >= 0 ? `${daysLeft} Days` : "Expired";
        } else {
            document.getElementById(outputId).value = "";
        }
    }

    document.getElementById('eos_hardware').addEventListener('change', function() {
        calculateTimeLeft('eos_hardware', 'hardware_time_left');
    });

    document.getElementById('eos_firmware').addEventListener('change', function() {
        calculateTimeLeft('eos_firmware', 'firmware_time_left');
    });

    document.getElementById('licence_end_date').addEventListener('change', function() {
        calculateTimeLeft('licence_end_date', 'license_time_left');
    });

    document.getElementById('ats_end_date').addEventListener('change', function() {
        calculateTimeLeft('ats_end_date', 'ats_time_left');
    });
</script>
@endsection

