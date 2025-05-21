@extends('layouts.app')

@section('title', 'ESA App')

@section('content')
<style>
    thead.custom-bright {
        background-color: #2a43d3;
        color: #d1dbdd;
    }
</style>

<h1 class="h3 mb-4 text-gray-800">ESAutomation</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        @include('partials.filter')

       <a href="{{ route('sales-orders.export') . '?' . http_build_query(request()->only(['customer', 'SO', 'CustPO', 'year', 'delivered_status'])) }}" class="btn btn-success mb-3">
    <i class="fas fa-file-excel"></i> Export Sales Orders
</a>

        @if(request()->hasAny(['customer', 'SO', 'CustPO']))
            <div class="alert alert-info small">
                <strong>Filter aktif:</strong>
                @foreach (['customer', 'SO', 'CustPO'] as $filter)
                    @if(request($filter))
                        {{ ucfirst($filter) }} = <span class="badge bg-primary">{{ request($filter) }}</span>
                    @endif
                @endforeach
                <a href="{{ route('sales-orders.index') }}" class="btn btn-sm btn-outline-danger ml-2">Reset Filter</a>
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped small" width="100%" cellspacing="0">
                <thead class="custom-bright">
                    <tr>
                        <th>Action</th>
                        <th>ID</th>
                        <th>SO</th>
                        <th>PO No</th>
                        <th>Nama Cust</th>
                        <th>Ship-to Code</th>
                        <th>Part No</th>
                        <th>Description</th>
                        <th>Outstanding Qty</th>
                        <th>Order Date</th>
                        <th>Receiving From Warehouse</th>
                        <th>Posting Date DO</th>
                        <th>Delayed SO to RCV Warehouse (Days)</th>
                        <th>Delayed Delivery to Cust (Days)</th>
                        <th>Delivered Status</th>
                        <th>Outstanding Amount</th>
                        <th>Sales Person</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $today = \Carbon\Carbon::now();
                    @endphp
                    @forelse($salesOrders as $order)
                        @php
                            $poRcvDate = \App\Models\PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
                            $postingDate = \App\Models\DeliveryOrder::where('OrderNo', $order->SO)
                                        ->latest('DeliveryDate')
                                        ->value('DeliveryDate');
                            $orderDate = $order->OrderDate ? \Carbon\Carbon::parse($order->OrderDate) : null;
                           
                           // Menghitung Delayed SO to RCV Warehouse
                    $delayedSoToRcv = 0;

                    if ($order->OrderDate) {
                        $orderDate = \Carbon\Carbon::parse($order->OrderDate)->startOfDay();

                        if ($poRcvDate) {
                            $rcvDate = \Carbon\Carbon::parse($poRcvDate)->startOfDay();
                            $delayedSoToRcv = $orderDate->diffInDays($rcvDate);
                        } elseif ($postingDate && !$poRcvDate) {
                            $delayedSoToRcv = '-';
                        } else {
                            $delayedSoToRcv = $orderDate->diffInDays(now()->startOfDay());
                        }
                    }

                            $delayedDeliveryToCust = '-'; // Nilai default jika semua data tidak tersedia

                            if ($poRcvDate && $postingDate) {
                                // Barang sudah diterima dan sudah dikirim
                                $delayedDeliveryToCust = \Carbon\Carbon::parse($poRcvDate)
                                    ->diffInDays(\Carbon\Carbon::parse($postingDate));
                            } elseif ($poRcvDate && !$postingDate) {
                                // Barang diterima tapi belum dikirim
                                $delayedDeliveryToCust = \Carbon\Carbon::parse($poRcvDate)
                                    ->diffInDays($today);
                            } elseif (!$poRcvDate && $postingDate && $order->OrderDate) {
                                // Barang belum diterima, tapi sudah dikirim (kasus anomali)
                                $delayedDeliveryToCust = \Carbon\Carbon::parse($order->OrderDate)
                                    ->diffInDays(\Carbon\Carbon::parse($postingDate));
                            }


                            $deliveredStatus = $order->CompletelyShipped || $postingDate ? 'Delivered' : ($poRcvDate ? 'Ready to Ship' : 'Indent');
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('sales-orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->SO }}</td>
                            <td>{{ $order->CustPO }}</td>
                            <td>{{ $order->Customer }}</td>
                            <td>{{ $order->ShipTo }}</td>
                            <td>{{ $order->PartNo }}</td>
                            <td>{{ $order->Description }}</td>
                            <td>{{ $order->OutstandingQty }}</td>
                            <td>{{ $order->OrderDate ? \Carbon\Carbon::parse($order->OrderDate)->format('Y-m-d') : '-' }}</td> <!-- Menampilkan Order Date -->
                            <td>
                                @if($poRcvDate)
                                    {{ \Carbon\Carbon::parse($poRcvDate)->format('Y-m-d') }}
                                @elseif($postingDate && !$poRcvDate && $order->OrderDate)
                                    {{ \Carbon\Carbon::parse($order->OrderDate)->format('Y-m-d') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $postingDate ? \Carbon\Carbon::parse($postingDate)->format('Y-m-d') : '-' }}</td>
                            <td>{{ $delayedSoToRcv ?? '-' }}</td>
                            <td>{{ $delayedDeliveryToCust ?? '-' }}</td>
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
                            <td>{{ number_format($order->TotalAmount, 2) }}</td>
                            <td>{{ $order->SalesPerson }}</td>
                            <td>{{ $order->Notes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="text-center">No data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
    {{ $salesOrders->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

        </div>
    </div>
</div>
@endsection
