@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Flex container for heading and buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Non-Hardware</h1>
        <div>
            <!-- Kategori Button -->
            @can('add category')
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#kategoriModal">
                <i class="fas fa-list"></i> Kategori
            </button>
            @endcan
            <a href="{{ route('nonhardware.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus"></i> Perangkat
            </a>
            @can('generate reports')
            <button class="btn btn-primary" onclick="window.location='{{ route('nonhardware.export') }}'">
                Export ke Excel
            </button>
            @endcan
        </div>
    </div>

    <!-- Search bar -->
    @can('search device data')
    <div class="mb-3">
        <form action="{{ route('nonhardware.index') }}" method="GET" class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Hostname/Brand/Serial Number/Kategori..." name="search" value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
    @endcan

    <!-- Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Hostname</th>
                <th>Kategori</th>
                <th>Serial Number</th>
                <th>Lokasi Perangkat</th>
                <th>License End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hardwares as $index => $hardware)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $hardware->HOST_NAME }}</td>
                <td>{{ $hardware->CATEGORY }}</td>
                <td>{{ $hardware->SERIAL_NUMBER }}</td>
                <td>{{ $hardware->LOCATION }}</td>
                <td>{{ $hardware->LICENCE_END_DATE }}</td>
                <td>
                    <a href="{{ route('nonhardware.show', $hardware->PERANGKAT_ID) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $hardwares->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

<!-- Kategori Modal -->
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategoriModalLabel" style="font-weight: bold;">Kategori Hardware</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($categories->isEmpty())
                    <p class="text-center text-muted">Belum ada kategori yang tersedia.</p>
                @else
                    <ul class="list-group mb-3">
                        @foreach($categories as $category)
                            <li class="list-group-item d-flex align-items-center">
                                <span>&bull; {{ $category->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <form action="{{ route('nonhardware.category.store') }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    <input type="text" name="name" class="form-control me-2" placeholder="Kategori Baru..." required>
                    <input type="hidden" name="type" value="Non-Hardware">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
