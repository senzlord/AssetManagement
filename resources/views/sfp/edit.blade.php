@extends('layouts.sidebar-layout')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/use-bootstrap-tag@2.2.2/dist/use-bootstrap-tag.min.css" rel="stylesheet">
@endsection

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
        @method('PUT') <!-- Include this for update -->

        <!-- Save and Cancel buttons -->
        <div class="d-flex justify-content-end mb-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('sfp.index') }}" class="btn btn-danger">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>

        <!-- Table layout for input fields -->
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="width: 25%; background-color: #f8f9fa;"><strong>No.</strong></td>
                    <td>
                        <input type="text" class="form-control" name="no" value="{{ $sfp->PERANGKAT_ID }}" disabled>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Location</strong></td>
                    <td>
                        <input type="text" class="form-control @error('LOCATION') is-invalid @enderror" name="LOCATION" placeholder="Location..." value="{{ old('LOCATION', $sfp->LOCATION) }}" required>
                        @error('LOCATION')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Vendor</strong></td>
                    <td>
                        <input type="text" class="form-control @error('VENDOR') is-invalid @enderror" name="VENDOR" placeholder="Vendor..." value="{{ old('VENDOR', $sfp->VENDOR) }}" required>
                        @error('VENDOR')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>PRODUCT-ID DEVICE</strong></td>
                    <td>
                        <input type="text" class="form-control @error('PRODUCT_ID_DEVICE') is-invalid @enderror" name="PRODUCT_ID_DEVICE" placeholder="PRODUCT-ID DEVICE..." value="{{ old('PRODUCT_ID_DEVICE', $sfp->PRODUCT_ID_DEVICE) }}" required>
                        @error('PRODUCT_ID_DEVICE')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Serial Number Device</strong></td>
                    <td>
                        <input type="text" class="form-control @error('SERIAL_NUMBER') is-invalid @enderror" name="SERIAL_NUMBER" placeholder="Serial Number Device..." value="{{ old('SERIAL_NUMBER', $sfp->SERIAL_NUMBER) }}" required>
                        @error('SERIAL_NUMBER')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f8f9fa;"><strong>Hostname</strong></td>
                    <td>
                        <input type="text" class="form-control @error('HOST_NAME') is-invalid @enderror" name="HOST_NAME" placeholder="Hostname..." value="{{ old('HOST_NAME', $sfp->HOST_NAME) }}" required>
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
                        <input type="number" class="form-control readonly-disabled @error('JUMLAH_SFP_DICABUT') is-invalid @enderror" name="JUMLAH_SFP_DICABUT" placeholder="Jumlah SFP Dicabut..." value="{{ old('JUMLAH_SFP_DICABUT', $sfp->JUMLAH_SFP_DICABUT) }}" required readonly>
                        @error('JUMLAH_SFP_DICABUT')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- New Dynamic Form for SFP Entries -->
        <h4 class="mt-4 d-flex justify-content-between align-items-center">
            <span>SFP Entries</span>
            <button type="button" class="btn btn-secondary" id="addSfpRow">
                <i class="fas fa-plus"></i> Add SFP
            </button>
        </h4>
        <table class="table table-bordered" id="sfpTable">
            <thead>
                <tr>
                    <th>Product-ID</th>
                    <th>Serial Number</th>
                    <th>Actions</th>
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
                                <input type="text" class="form-control" name="SFP[product_id][]" value="{{ $productId }}" required />
                            </td>
                            <td>
                                <input type="text" class="form-control serial-number-input" name="SFP[serial_number][]" value="{{ implode(',', (array)$sfpEntries['serial_number'][$index]) }}" required />
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger removeRow">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
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
    </form>
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

            tagInstances.push({ element: input.closest('tr'), instance: tagInstance });

            const serialNumberContainer = input.closest('tr').querySelector('.use-bootstrap-tag');
            const serialNumberTagsInput = serialNumberContainer.querySelector('input');
            serialNumberTagsInput.addEventListener('blur', function () {
                recalculateTotalTags();
            });
        });

        // Recalculate total tags on page load
        recalculateTotalTags();
    });

    // Add new row dynamically
    document.getElementById('addSfpRow').addEventListener('click', function () {
        const tableBody = document.getElementById('sfpTable').querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <input type="text" class="form-control" name="SFP[product_id][]" placeholder="Product-ID..." required/>
            </td>
            <td>
                <input type="text" class="form-control serial-number-input" name="SFP[serial_number][]" placeholder="Add Serial Numbers..." required/>
            </td>
            <td>
                <button type="button" class="btn btn-danger removeRow">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);

        const lastRow = tableBody.lastElementChild;
        const serialNumberInput = lastRow.querySelector('.serial-number-input');
        
        // Initialize UseBootstrapTag
        const tagInstance = UseBootstrapTag(serialNumberInput);

        // Add the instance to the tagInstances array
        tagInstances.push({ element: lastRow, instance: tagInstance });

        const serialNumberContainer = lastRow.querySelector('.use-bootstrap-tag');
        const serialNumberTagsInput = serialNumberContainer.querySelector('input');
        serialNumberTagsInput.addEventListener('blur', function () {
            recalculateTotalTags();
        });
    });

    // Remove row functionality
    document.getElementById('sfpTable').addEventListener('click', function (e) {
        if (e.target.classList.contains('removeRow') || e.target.closest('.removeRow')) {
            const row = e.target.closest('tr');

            // Remove the associated tagInstance by finding the matching row
            const index = tagInstances.findIndex(item => item.element === row);
            if (index > -1) {
                tagInstances.splice(index, 1); // Remove the instance from the array
            }

            row.remove(); // Remove the row from the DOM

            // Recalculate total tags after row removal
            recalculateTotalTags();
        }
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
