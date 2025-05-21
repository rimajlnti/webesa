@extends('layouts.app')

@section('title', 'Daftar Customer Aktif')

@section('content')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

<div class="container-fluid px-4">
    <h1 class="h3 mb-4 fw-semibold text-dark">Daftar Customer Aktif</h1>

    <!-- Kartu Tabel Customer -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Customer</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama Customer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeCustomers as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('customer.salesorders', ['customer' => $customer->Customer]) }}" class="text-decoration-none fw-medium text-primary">
                                    {{ $customer->Customer }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kartu Sales Orders -->
    <div id="sales-orders-container" class="card border-0 shadow-sm mb-5" style="display: none;">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Sales Orders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>SO Number</th>
                            <th>Posting Date</th>
                            <th>Customer</th>
                            <th>Delivered Status</th>
                        </tr>
                    </thead>
                    <tbody id="sales-orders-list">
                        <!-- Diisi lewat JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('js')
<script>
$(document).on('click', '.customer-link', function () {
    const customerName = encodeURIComponent($(this).data('customer'));
    alert('Klik berhasil: ' + customerName);
    console.log('Customer clicked:', customerName);

    fetch(`/sales-orders/${customerName}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetched data:', data);

            const container = document.getElementById('sales-orders-container');
            const tableBody = document.getElementById('sales-orders-list');
            tableBody.innerHTML = '';

            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data SO untuk customer ini.</td>
                    </tr>`;
            } else {
                data.forEach((order, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${order.SO}</td>
                        <td>${order.PostingDate ? order.PostingDate.split('T')[0] : ''}</td>
                        <td>${order.Customer}</td>
                        <td>${order.DeliveredStatus ?? '-'}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            container.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching sales orders:', error);
            alert('Gagal mengambil data SO');
        });
});
</script>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@endsection
