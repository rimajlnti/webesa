@extends('layouts.app')

@section('title', 'Detail Sales Order')

@section('content')
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<h1 class="h3 mb-4 text-gray-800">Detail Sales Order</h1>

<div class="card">
    <div class="card-body">
        <a href="{{ route('sales-orders.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

        <table class="table table-bordered table-striped small">
            <tr><th>ID</th><td>{{ $order->id }}</td></tr>
            <tr><th>Sales Order (SO)</th><td>{{ $order->SO }}</td></tr>
            <tr><th>PO No</th><td>{{ $order->CustPO }}</td></tr>
            <tr><th>Customer</th><td>{{ $order->Customer }}</td></tr>
            <tr><th>Ship To</th><td>{{ $order->ShipTo }}</td></tr>
            <tr><th>Part No</th><td>{{ $order->PartNo }}</td></tr>
            <tr><th>Description</th><td>{{ $order->Description }}</td></tr>
            <tr><th>Outstanding Qty</th><td>{{ $order->OutstandingQty }}</td></tr>
            <tr><th>Order Date</th><td>{{ $order->OrderDate ? \Carbon\Carbon::parse($order->OrderDate)->format('Y-m-d') : '-' }}</td></tr>
            <tr><th>Receiving From Warehouse</th><td>{{ $poRcvDate ? $poRcvDate->format('Y-m-d') : ($postingDate && !$poRcvDate && $order->OrderDate ? \Carbon\Carbon::parse($order->OrderDate)->format('Y-m-d') : '-') }}</td></tr>
            <tr><th>Posting Date DO</th><td>{{ $postingDate ? $postingDate->format('Y-m-d') : '-' }}</td></tr>
            <tr><th>Delayed SO to RCV Warehouse (Days)</th><td>{{ $delayedSoToRcv ?? '-' }}</td></tr>
            <tr><th>Delayed Delivery to Cust (Days)</th><td>{{ $delayedDeliveryToCust ?? '-' }}</td></tr>
            <tr><th>Delivered Status</th>
                <td>
                    @switch($deliveredStatus)
                        @case('Delivered')
                            <span class="badge bg-success text-white">✔ {{ $deliveredStatus }}</span>
                            @break
                        @case('Ready to Ship')
                            <span class="badge bg-warning text-white">🚚 {{ $deliveredStatus }}</span>
                            @break
                        @case('Indent')
                            <span class="badge bg-danger text-white">⏳ {{ $deliveredStatus }}</span>
                            @break
                        @default
                            <span class="badge bg-secondary text-white">{{ $deliveredStatus }}</span>
                    @endswitch
                </td>
            </tr>
            <tr><th>Outstanding Amount</th><td>{{ number_format($order->TotalAmount, 2) }}</td></tr>
            <tr><th>Sales Person</th><td>{{ $order->SalesPerson }}</td></tr>
            <tr><th>Notes</th><td>{{ $order->Notes }}</td></tr>
        </table>

        <hr>

        <form method="POST" action="{{ route('sales-orders.update', $order->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="Notes">Catatan / Notes</label>
                <textarea name="Notes" id="Notes" class="form-control" rows="3">{{ old('Notes', $order->Notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
        </form>

        <form method="POST" action="{{ route('sales-orders.clear-notes', $order->id) }}" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');" class="mt-2">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-danger">Hapus Catatan</button>
        </form>
    </div>
</div>
@endsection
