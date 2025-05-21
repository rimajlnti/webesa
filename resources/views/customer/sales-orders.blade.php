@extends('layouts.app')

@section('title', 'Sales Order - ' . $customer)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Sales Orders untuk {{ $customer }}</h1>

    <!-- Kartu Tabel Customer -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $customer }}</h5>
        </div>
        <div class="card-body">
            @if($salesOrders->isEmpty())
                <p class="mb-0">Tidak ada Sales Order untuk customer ini.</p>
            @else
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>SO Number</th>
                            <th>Posting Date</th>
                            <th>Customer</th>
                            <th>Delivered Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $today = \Carbon\Carbon::now();
                        @endphp
                        @foreach($salesOrders as $index => $order)
                            @php
                                $poRcvDate = \App\Models\PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
                                $postingDate = \App\Models\DeliveryOrder::where('OrderNo', $order->SO)->latest('DeliveryDate')->value('DeliveryDate');
                                $orderDate = $order->OrderDate ? \Carbon\Carbon::parse($order->OrderDate) : null;

                                $deliveredStatus = $order->CompletelyShipped || $postingDate ? 'Delivered' : ($poRcvDate ? 'Ready to Ship' : 'Indent');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->SO }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->PostingDate)->format('Y-m-d') }}</td>
                                <td>{{ $order->Customer }}</td>
                                <td>
                                    @if($deliveredStatus === 'Delivered')
                                        <span class="badge bg-success text-white">✔ {{ $deliveredStatus }}</span>
                                    @elseif($deliveredStatus === 'Ready to Ship')
                                        <span class="badge bg-warning text-white">🚚 {{ $deliveredStatus }}</span>
                                    @elseif($deliveredStatus === 'Indent')
                                        <span class="badge bg-danger text-white">⏳ {{ $deliveredStatus }}</span>
                                    @else
                                        <span class="badge bg-secondary text-white">{{ $deliveredStatus }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div> <!-- penutup card-body -->
    </div> <!-- penutup card -->
</div> <!-- penutup container -->

<a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Kembali</a>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@endsection
