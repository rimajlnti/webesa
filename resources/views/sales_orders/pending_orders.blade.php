@extends('layouts.app')

@section('title', 'Pending Orders')

@push('styles')
<!-- Bootstrap 5 sudah ada, hanya perlu tambah style khusus -->
<style>
    /* Header Table dengan gradien warna biru */
    thead.table-light {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    /* Hover baris tabel dengan bayangan dan warna lembut */
    tbody tr:hover {
        background-color: #f0f4ff;
        box-shadow: 0 2px 8px rgba(34, 74, 190, 0.15);
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    /* Warna teks untuk badge lebih modern */
    .badge-status-delivered {
        background-color: #1cc88a;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge-status-ready {
        background-color: #f6c23e;
        color: #5a5c69;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge-status-indent {
        background-color: #e74a3b;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Box shadow untuk card */
    .card.shadow-sm {
        border-radius: 0.75rem;
        box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
        border: none;
    }

    /* Responsive scrollbar style */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #4e73df;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-primary fw-bold">Pending Orders</h1>

    @if($pendingOrders->count())
    <div class="card shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-nowrap mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Order No</th>
                            <th>Order Date</th>
                            <th>Customer</th>
                            <th>PO</th>
                            <th>Part No</th>
                            <th style="width: 130px;">Outstanding Qty</th>
                            <th style="width: 140px;">Delivered Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingOrders as $index => $order)
                            @php
                                $poRcvDate = \App\Models\PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
                                $postingDate = \App\Models\DeliveryOrder::where('OrderNo', $order->SO)->latest('DeliveryDate')->value('DeliveryDate');
                                $deliveredStatus = $order->CompletelyShipped || $postingDate ? 'Delivered' : ($poRcvDate ? 'Ready to Ship' : 'Indent');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $order->SO }}</td>
                                <td class="text-center text-muted">{{ \Carbon\Carbon::parse($order->OrderDate)->format('d M Y') }}</td>
                                <td>{{ $order->Customer }}</td>
                                <td class="text-center">{{ $order->CustPO }}</td>
                                <td>{{ $order->PartNo }}</td>
                                <td class="text-end fw-bold text-primary">{{ number_format($order->OutstandingQty) }}</td>
                                <td class="text-center">
                                    @if($deliveredStatus === 'Delivered')
                                        <span class="badge badge-status-delivered"><i class="fas fa-check-circle me-1"></i>Delivered</span>
                                    @elseif($deliveredStatus === 'Ready to Ship')
                                        <span class="badge badge-status-ready"><i class="fas fa-truck me-1"></i>Ready to Ship</span>
                                    @elseif($deliveredStatus === 'Indent')
                                        <span class="badge badge-status-indent"><i class="fas fa-hourglass-half me-1"></i>Indent</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $deliveredStatus }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info shadow-sm rounded-3">
        <i class="fas fa-info-circle me-2"></i> Tidak ada pending order saat ini.
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
