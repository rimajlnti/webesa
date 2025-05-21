@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Sales Orders Delayed ({{ $range }})</h4>

    @if(count($filteredOrders))
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SO Number</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Delayed Days</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filteredOrders as $order)
                    <tr>
                        <td>{{ $order->SO }}</td>
                        <td>{{ $order->Customer }}</td>
                        <td>{{ $order->OrderDate }}</td>
                        <td>{{ $order->delayed_days }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No delayed sales orders found for this range.</p>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
@endsection
