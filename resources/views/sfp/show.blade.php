@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Flex container for heading and button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Detail Perangkat SFP</h1>
        <div>
            <div class="d-flex justify-content-end mt-3">
            @can('edit device data')
                <a href="{{ route('sfp.edit', $sfp->PERANGKAT_ID) }}" class="btn btn-success me-2">
                    <i class="fas fa-edit"></i> Edit Perangkat
                </a>
            @endcan
            @can('delete device data')
                <button type="button" class="btn btn-danger me-2" onclick="confirmDelete('{{ route('sfp.destroy', $sfp->PERANGKAT_ID) }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            @endcan
                <a href="{{ route('sfp.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
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
                        <td style="background-color: #f8f9fa;"><strong>Jumlah SFP Tersedia</strong></td>
                        <td>{{ $sfp->JUMLAH_SFP_DICABUT }}</td>
                    </tr>
                </tbody>
            </table>
            <!-- New Dynamic Form for SFP Entries -->
            <h4 class="mt-4 d-flex justify-content-between align-items-center">
                <span>SFP Entries</span>
            </h4>
            <table class="table table-bordered" id="sfpTable">
                <thead>
                    <tr>
                        <th>Product-ID</th>
                        <th>Serial Number</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sfpEntries = json_decode($sfp->SFP, true);
                    @endphp
    
                    @if (!empty($sfpEntries['product_id']) && !empty($sfpEntries['serial_number']))
                        @foreach ($sfpEntries['product_id'] as $index => $productId)
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="SFP[product_id][]" value="{{ $productId }}" disabled />
                                </td>
                                <td>
                                    <input type="hidden" class="form-control serial-number-input" name="SFP[serial_number][]" value="{{ $sfpEntries['serial_number'][$index] }}" disabled />
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No SFP data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/use-bootstrap-tag@2.2.2/dist/use-bootstrap-tag.min.js"></script>
<script>
    const tagInstances = [];

    document.addEventListener('DOMContentLoaded', function () {
        const serialInputs = document.querySelectorAll('.serial-number-input');

        serialInputs.forEach(input => {
            const tagInstance = UseBootstrapTag(input);
        });
        // Recalculate total tags on page load
        recalculateTotalTags();
    });

    function recalculateTotalTags() {
        const totalTags = tagInstances.reduce((sum, item) => sum + item.instance.getValues().length, 0);
        // console.log('Total tags across all rows:', totalTags);

        const jumlahSfpInput = document.querySelector('input[name="JUMLAH_SFP_DICABUT"]');
        if (jumlahSfpInput) {
            jumlahSfpInput.value = totalTags; // Update the value
        }
    }
</script>
@endsection