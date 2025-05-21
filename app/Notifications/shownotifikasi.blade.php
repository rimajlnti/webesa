@extends('layouts.app')

@section('content')
<h3>Notifikasi: 
    @if($type == 'notdelivered') SO Belum Delivered
    @elseif($type == 'delayed') SO Terlambat > 7 Hari
    @endif
</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>SO</th>
            <th>Customer</th>
            <th>PO No</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($filtered as $order)
            <tr>
                <td>{{ $order->SO }}</td>
                <td>{{ $order->Customer }}</td>
                <td>{{ $order->CustPO }}</td>
                <td>{{ $order->OrderDate }}</td>
                <td>{{ $order->CompletelyShipped ? 'Delivered' : 'Belum Delivered' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Tidak ada data ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
