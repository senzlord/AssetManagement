@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Heading -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Tambah Perangkat Non-Hardware</h1>
    </div>

    <form action="{{ route('nonhardware.store') }}" method="POST">
        @csrf

        <!-- Save and Cancel buttons -->
        <div class="d-flex justify-content-end mb-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('nonhardware.index') }}" class="btn btn-danger">
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
                        <input type="text" class="form-control @error('HOST_NAME') is-invalid @enderror" name="HOST_NAME" placeholder="Hostname..." value="{{ old('HOST_NAME') }}" required>
                        @error('HOST_NAME')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Brand</strong></td>
                    <td>
                        <input type="text" class="form-control @error('BRAND') is-invalid @enderror" name="BRAND" placeholder="Brand..." value="{{ old('BRAND') }}" required>
                        @error('BRAND')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Kategori</strong></td>
                    <td>
                        <select class="form-control @error('CATEGORY') is-invalid @enderror" name="CATEGORY" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ old('CATEGORY') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('CATEGORY')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Serial Number</strong></td>
                    <td>
                        <input type="text" class="form-control @error('SERIAL_NUMBER') is-invalid @enderror" name="SERIAL_NUMBER" placeholder="Serial Number..." value="{{ old('SERIAL_NUMBER') }}" required>
                        @error('SERIAL_NUMBER')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Lokasi Perangkat</strong></td>
                    <td>
                        <input type="text" class="form-control @error('LOCATION') is-invalid @enderror" name="LOCATION" placeholder="Lokasi Perangkat..." value="{{ old('LOCATION') }}" required>
                        @error('LOCATION')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>PIC</strong></td>
                    <td>
                        <input type="text" class="form-control @error('USER') is-invalid @enderror" name="USER" placeholder="User..." value="{{ old('USER') }}" required>
                        @error('USER')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                    <td>
                        <input type="text" class="form-control @error('VENDOR') is-invalid @enderror" name="VENDOR" placeholder="Vendor..." value="{{ old('VENDOR') }}" required>
                        @error('VENDOR')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
            </tbody>
        </table>
    
        <!-- Firmware / OS Section -->
        <h4 class="mt-4">Firmware / OS</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>Firmware Version</strong></td>
                    <td>
                        <input type="text" class="form-control @error('FIRMWARE') is-invalid @enderror" name="FIRMWARE" placeholder="Version..." value="{{ old('FIRMWARE') }}" required>
                        @error('FIRMWARE')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>OS Version</strong></td>
                    <td>
                        <input type="text" class="form-control @error('OS_VERSION') is-invalid @enderror" name="OS_VERSION" placeholder="Version..." value="{{ old('OS_VERSION') }}" required>
                        @error('OS_VERSION')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>End-of-Support</strong></td>
                    <td>
                        <input type="date" class="form-control @error('EOS_FIRMWARE') is-invalid @enderror" name="EOS_FIRMWARE" id="eos_firmware">
                        @error('EOS_FIRMWARE')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
                        <input type="date" class="form-control @error('LICENCE_END_DATE') is-invalid @enderror" name="LICENCE_END_DATE" id="licence_end_date">
                        @error('LICENCE_END_DATE')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
                        <input type="text" class="form-control @error('NAMA_KONTRAK') is-invalid @enderror" name="NAMA_KONTRAK" placeholder="Nama..." value="{{ old('NAMA_KONTRAK') }}" required>
                        @error('NAMA_KONTRAK')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>No Kontrak</strong></td>
                    <td>
                        <input type="text" class="form-control @error('NO_KONTRAK') is-invalid @enderror" name="NO_KONTRAK" placeholder="No..." value="{{ old('NO_KONTRAK') }}" required>
                        @error('NO_KONTRAK')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Status Support</strong></td>
                    <td>
                        <select class="form-control @error('STATUS_SUPPORT') is-invalid @enderror" name="STATUS_SUPPORT" required>
                            <option value="">Pilih Status Support</option>
                            <option value="Support" {{ old('STATUS_SUPPORT') == 'Support' ? 'selected' : '' }}>Support</option>
                            <option value="Tidak Support" {{ old('STATUS_SUPPORT') == 'Tidak Support' ? 'selected' : '' }}>Tidak Support</option>
                        </select>
                        @error('STATUS_SUPPORT')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>End Date</strong></td>
                    <td>
                        <input type="date" class="form-control @error('ATS_END_DATE') is-invalid @enderror" name="ATS_END_DATE" id="ats_end_date">
                        @error('ATS_END_DATE')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                    <td>
                        <input type="text" class="form-control" id="ats_time_left" placeholder="Calculated automatically..." disabled>
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
