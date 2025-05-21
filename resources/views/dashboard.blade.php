@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Page Heading -->
<h2>Selamat Datang, {{ Auth::user()->name }}</h2>
{{-- @if(Auth::user()->isAdmin())
        <p>Anda login sebagai <strong>Admin</strong>.</p> --}}
        <!-- Konten khusus admin -->
        {{-- <a href="{{ route('manajemen.user') }}">Kelola Pengguna</a> --}}
    {{-- @elseif(Auth::user()->isSales())
        <p>Anda login sebagai <strong>Sales</strong>.</p> --}}
        <!-- Konten khusus sales -->
        {{-- <a href="{{ route('penjualan.index') }}">Data Penjualan</a> --}}
    {{-- @elseif(Auth::user()->isIT())
        <p>Anda login sebagai <strong>IT</strong>.</p> --}}
        <!-- Konten khusus IT -->
        {{-- <a href="{{ route('system.logs') }}">Log Sistem</a> --}}
    {{-- @elseif(Auth::user()->isDirector())
        <p>Anda login sebagai <strong>Direktur</strong>.</p> --}}
        <!-- Konten khusus direktur -->
        {{-- <a href="{{ route('laporan.index') }}">Laporan Penjualan</a> --}}
    {{-- @else
        <p>Role tidak dikenali.</p>
    @endif --}}
<div class="row">
    @foreach ([ 
        ['title' => 'Total Sales Orders', 'value' => $totalOrders, 'route' => 'sales-orders.index', 'icon' => 'fas fa-shopping-cart', 'color' => 'primary'],
        ['title' => 'Active Customers', 'value' => $activeCustomersCount, 'route' => 'sales-orders.active-customers', 'icon' => 'fas fa-users', 'color' => 'success'],
        ['title' => 'Pending Orders', 'value' => $pendingOrdersCount, 'route' => 'sales-orders.pending-orders', 'icon' => 'fas fa-hourglass-half', 'color' => 'warning']
    ] as $card)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                            {{ $card['title'] }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $card['value'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="{{ $card['icon'] }} fa-2x text-gray-300"></i>
                    </div>
                </div>
                <a href="{{ route($card['route']) }}" class="small text-{{ $card['color'] }} mt-2 d-block">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- Grafik Keterlambatan Pengiriman -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Grafik SO Ready To Ship</h6>
    </div>
    <div class="card-body">
        <div class="chart-area" style="height: 400px;">
            <canvas id="DelayChart"></canvas>
        </div>
    </div>
</div>
<!-- Grafik Keterlambatan Pengiriman -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">History Keterlambatan Pengiriman</h6>
    </div>
    <div class="card-body">
        <div class="chart-area" style="height: 400px;">
            <canvas id="deliveryDelayChart"></canvas>
        </div>
    </div>
</div>
<!-- Diagram Lingkaran Keterlambatan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Diagram Lingkaran Keterlambatan</h6>
    </div>
    <div class="card-body">
        <div class="chart-pie pt-4 pb-2">
            <canvas id="delayPieChart"></canvas>
        </div>
    </div>
</div>


@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Keterlambatan Pengiriman
    const ctx1 = document.getElementById('DelayChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($delayLabels),  // Kategori keterlambatan
            datasets: [{
                label: 'Jumlah SO',
                data: @json($delayValue),  // Jumlah SO di setiap kategori
                backgroundColor: [
                '#4bc0c0',  // Warna solid hijau
                '#9966ff',  // Warna solid ungu
                '#ff9f40'   // Warna solid oranye
            ],
            borderColor: [
                '#4bc0c0',  // Warna solid hijau
                '#9966ff',  // Warna solid ungu
                '#ff9f40'   // Warna solid oranye
            ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            onClick: function (event, elements) {
                if (elements.length > 0) {
                    const clickedIndex = elements[0].index;
                    const clickedLabel = this.data.labels[clickedIndex];

                    // Menentukan parameter berdasarkan label
                    let typeParam = '';

                    if (clickedLabel === '1-6 days') {
                        typeParam = 'delayeddata';
                    } else if (clickedLabel === '7-14 days') {
                        typeParam = 'delayed';
                    } else if (clickedLabel === '14+ days') {
                        typeParam = 'delay';
                    }
                     // Redirect ke URL dengan parameter
                    if (typeParam !== '') {
                        window.location.href = `http://127.0.0.1:8000/notifikasi/detail?type=${typeParam}`;
                    }
                }
            }
        }
    });
</script>
<script>
    // Grafik Keterlambatan Pengiriman
    const ctx2 = document.getElementById('deliveryDelayChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($delayLabels),  // Kategori keterlambatan
            datasets: [{
                label: 'Jumlah SO',
                data: @json($delayValues),  // Jumlah SO di setiap kategori
                backgroundColor: [
                '#4bc0c0',  // Warna solid hijau
                '#9966ff',  // Warna solid ungu
                '#ff9f40'   // Warna solid oranye
            ],
            borderColor: [
                '#4bc0c0',  // Warna solid hijau
                '#9966ff',  // Warna solid ungu
                '#ff9f40'   // Warna solid oranye
            ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,  // Memulai dari 0
                    ticks: {
                        stepSize: 5,  // Mengatur interval antara setiap tick di sumbu Y (0, 5, 10, 15, 20, 25)
                        max: 25        // Membatasi nilai maksimal pada sumbu Y (hingga 25)
                    }
                }
            }
        }
    });
    </script>
<script>
// Pie Chart untuk proporsi keterlambatan
const ctxPie = document.getElementById('delayPieChart').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: @json($delayLabels), // ['1-6 days', '7-14 days', '14+ days']
        datasets: [{
            data: @json($delayValues),
            backgroundColor: [
                '#4bc0c0',  // Warna solid hijau
                '#9966ff',  // Warna solid ungu
                '#ff9f40'   // Warna solid oranye
            ],
            borderColor: [
                '#4bc0c0',  // Warna solid hijau
                '#9966ff',  // Warna solid ungu
                '#ff9f40'   // Warna solid oranye
            ],
            borderWidth: 1
        }]
    },
    options: {
    responsive: true,
    maintainAspectRatio: false,
    aspectRatio: 1,  // Menentukan rasio aspek 1:1 untuk lingkaran
    plugins: {
        legend: {
            position: 'bottom'
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.label || '';
                    let value = context.raw;
                    let total = context.chart._metasets[0].total;
                    let percentage = ((value / total) * 100).toFixed(1);
                    return `${label}: ${value} SO (${percentage}%)`;
                }
            }
        }
    }
}

});
</script>
@endsection
