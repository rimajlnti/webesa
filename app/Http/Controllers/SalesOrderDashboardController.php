<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;

use Carbon\Carbon;
use App\Models\PoRcv;
use App\Models\DeliveryOrder;
use Illuminate\Support\Facades\DB;

class SalesOrderDashboardController extends Controller
{
    public function dashboard()
    {
        // Ambil data sales orders yang relevan
    $salesOrders = SalesOrder::all();

    // Inisialisasi array untuk kategori dan nilai delay
    $delayLabels = ['1-6 days', '7-14 days', '14+ days'];  // Kategori keterlambatan
    $delayValues = [0, 0, 0];  // Nilai jumlah SO dalam masing-masing kategori
    $delayValue = [0, 0, 0];  // Nilai jumlah SO dalam masing-masing kategori


foreach ($salesOrders as $order) {
    $poRcvDate = PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
    $postingDate = DeliveryOrder::where('OrderNo', $order->SO)->latest('DeliveryDate')->value('DeliveryDate');
    $orderDate = $order->OrderDate ? Carbon::parse($order->OrderDate) : null;
    $today = Carbon::now();

    $delayedDeliveryToCust = null;

    if ($poRcvDate && $postingDate) {
        $delayedDeliveryToCust = Carbon::parse($poRcvDate)->diffInDays(Carbon::parse($postingDate));
    } elseif ($poRcvDate && !$postingDate) {
        $delayedDeliveryToCust = Carbon::parse($poRcvDate)->diffInDays($today);
    } elseif (!$poRcvDate && $postingDate && $order->OrderDate) {
        $delayedDeliveryToCust = Carbon::parse($order->OrderDate)->diffInDays(Carbon::parse($postingDate));
    }

    if (is_numeric($delayedDeliveryToCust)) {
        if ($delayedDeliveryToCust >= 1 && $delayedDeliveryToCust <= 6) {
            $delayValues[0]++;
        } elseif ($delayedDeliveryToCust >= 7 && $delayedDeliveryToCust <= 14) {
            $delayValues[1]++;
        } elseif ($delayedDeliveryToCust > 14) {
            $delayValues[2]++;
        }
    }
    if (is_numeric($delayedDeliveryToCust)) {
        if ($delayedDeliveryToCust >= 1 && $delayedDeliveryToCust <= 6 && !$postingDate) {
            $delayValue[0]++;
        } elseif ($delayedDeliveryToCust >= 7 && $delayedDeliveryToCust <= 14 && !$postingDate) {
            $delayValue[1]++;
        } elseif ($delayedDeliveryToCust > 14 && !$postingDate) {
            $delayValue[2]++;
        }
    }
}


    // Kirim data ke view
    // return view('dashboard.index', [
    //     'delayLabels' => $delayLabels,
    //     'delayValues' => $delayValues
    // ]);

        return view('dashboard', [
'totalOrders' => SalesOrder::count(),
            'activeCustomersCount' => SalesOrder::distinct('Customer')->count(),
          'pendingOrdersCount' => DB::table('sales_orders as so')
    ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('delivery_orders as do')
              ->whereColumn('so.SO', 'do.OrderNo')
              ->whereNotNull('do.DeliveryDate');
    })
    ->count(),

            // 'labels' => $labels,
            // 'values' => $values,
            'delayLabels' => $delayLabels,
            'delayValues' => $delayValues,
            'delayValue' => $delayValue
        ]);
        
    }
}

            
