@extends('layouts.app')

@section('content')
<style>
    thead.custom-bright {
        background-color: #2a43d3;
        color: #ffffff;
    }
    .card-header {
        background-color: #2a43d3;
        color: white;
        font-weight: bold;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header">
        Detail Notifikasi:
        @if($type == 'notdelivered')
            Order Belum Delivered
        @elseif($type == 'delayeddata')
            Order Terlambat > 6 Hari
        @elseif($type == 'delayed')
            Order Terlambat > 7 Hari - 14 hari
        @elseif($type == 'delay')
            Order Terlambat > 14 Hari
        @else
            Semua Notifikasi
        @endif
    </div>

    <div class="card-body">
        @if($filteredOrders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tidak ada data notifikasi untuk kategori ini.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover small" width="100%" cellspacing="0">
                    <thead class="custom-bright">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>SO</th>
                            <th>PO</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Delivered Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $today = \Carbon\Carbon::now(); @endphp
                        @foreach($filteredOrders as $index => $order)
                            @php
                                $poRcvDate = \App\Models\PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
                                $postingDate = \App\Models\DeliveryOrder::where('OrderNo', $order->SO)
                                    ->latest('DeliveryDate')
                                    ->value('DeliveryDate');
                                $orderDate = $order->OrderDate ? \Carbon\Carbon::parse($order->OrderDate) : null;

                                $deliveredStatus = $order->CompletelyShipped || $postingDate ? 'Delivered' : ($poRcvDate ? 'Ready to Ship' : 'Indent');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->SO }}</td>
                                <td>{{ $order->PO }}</td>
                                <td>{{ $order->OrderDate }}</td>
                                <td>{{ $postingDate ?? '-' }}</td>
                                <td>
                                    @if($deliveredStatus === 'Delivered')
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-check-circle"></i> {{ $deliveredStatus }}
                                        </span>
                                    @elseif($deliveredStatus === 'Ready to Ship')
                                        <span class="badge bg-warning text-white">
                                            <i class="fas fa-truck"></i> {{ $deliveredStatus }}
                                        </span>
                                    @elseif($deliveredStatus === 'Indent')
                                        <span class="badge bg-danger text-white">
                                            <i class="fas fa-hourglass-half"></i> {{ $deliveredStatus }}
                                        </span>
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

        <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
