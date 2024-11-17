@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Heading -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Detail Perangkat Hardware</h1>
        <div>
            <div class="d-flex justify-content-end mt-3">
            @can('edit device data')
                <a href="{{ route('hardware.edit', $hardware->PERANGKAT_ID) }}" class="btn btn-success me-2">
                    <i class="fas fa-edit"></i> Edit Perangkat
                </a>
            @endcan
            @can('delete device data')
                <button type="button" class="btn btn-danger me-2" onclick="confirmDelete('{{ route('hardware.destroy', $hardware->PERANGKAT_ID) }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            @endcan
                <a href="{{ route('hardware.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%; background-color: #f8f9fa;"><strong>No.</strong></td>
                <td>{{ $hardware->PERANGKAT_ID }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Hostname</strong></td>
                <td>{{ $hardware->HOST_NAME }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Brand</strong></td>
                <td>{{ $hardware->BRAND }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Kategori</strong></td>
                <td>{{ $hardware->CATEGORY }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Serial Number</strong></td>
                <td>{{ $hardware->SERIAL_NUMBER }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>IP Address</strong></td>
                <td>{{ $hardware->IP_ADDRESS }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Lokasi Perangkat</strong></td>
                <td>{{ $hardware->LOCATION }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>PIC</strong></td>
                <td>{{ $hardware->USER }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                <td>{{ $hardware->VENDOR }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Hardware Section -->
    <h4 class="mt-4">Hardware</h4>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%; background-color: #f8f9fa;"><strong>End-of-Support</strong></td>
                <td id="eos_hardware_date">{{ $hardware->EOS_HARDWARE ? $hardware->EOS_HARDWARE->format('Y-m-d') : 'TBA' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                <td id="eos_hardware_time_left"></td>
            </tr>
        </tbody>
    </table>

    <!-- Firmware / OS Section -->
    <h4 class="mt-4">Firmware / OS</h4>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%; background-color: #f8f9fa;"><strong>Version</strong></td>
                <td>{{ $hardware->FIRMWARE }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>End-of-Support</strong></td>
                <td id="eos_firmware_date">{{ $hardware->EOS_FIRMWARE ? $hardware->EOS_FIRMWARE->format('Y-m-d') : 'TBA' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                <td id="eos_firmware_time_left"></td>
            </tr>
        </tbody>
    </table>

    <!-- Licenses Section -->
    <h4 class="mt-4">Licenses</h4>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%; background-color: #f8f9fa;"><strong>End Date</strong></td>
                <td id="licence_end_date">{{ $hardware->LICENCE_END_DATE ? $hardware->LICENCE_END_DATE->format('Y-m-d') : 'TBA' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                <td id="licence_time_left"></td>
            </tr>
        </tbody>
    </table>

    <!-- Kontrak / ATS Section -->
    <h4 class="mt-4">Kontrak / ATS</h4>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="width: 25%; background-color: #f8f9fa;"><strong>Nama Kontrak</strong></td>
                <td>{{ $hardware->NAMA_KONTRAK }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>No Kontrak</strong></td>
                <td>{{ $hardware->NO_KONTRAK }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Status Support</strong></td>
                <td>{{ $hardware->STATUS_SUPPORT }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>End Date</strong></td>
                <td id="ats_end_date">{{ $hardware->ATS_END_DATE ? $hardware->ATS_END_DATE->format('Y-m-d') : 'TBA' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>Time Left (Days)</strong></td>
                <td id="ats_time_left"></td>
            </tr>
            <tr>
                <td style="background-color: #f8f9fa;"><strong>PIC</strong></td>
                <td>{{ $hardware->PIC }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('script')
<script>
    function calculateTimeLeft(dateId, outputId) {
        const dateElement = document.getElementById(dateId);
        const outputElement = document.getElementById(outputId);

        if (dateElement && outputElement) {
            const dateValue = dateElement.innerText;
            if (dateValue !== '-' && dateValue) {
                const endDate = moment(dateValue, 'YYYY-MM-DD');
                const currentDate = moment();
                const daysLeft = endDate.diff(currentDate, 'days');
                outputElement.innerText = daysLeft >= 0 ? `${daysLeft} Days` : "Expired";
            } else {
                outputElement.innerText = "-";
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateTimeLeft('eos_hardware_date', 'eos_hardware_time_left');
        calculateTimeLeft('eos_firmware_date', 'eos_firmware_time_left');
        calculateTimeLeft('licence_end_date', 'licence_time_left');
        calculateTimeLeft('ats_end_date', 'ats_time_left');
    });
</script>
@endsection
