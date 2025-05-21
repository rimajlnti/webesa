<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\PoRcv;
use App\Models\DeliveryOrder;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::all();

        $notDeliveredCount = 0;
        $delayedMoreThan7Days = 0;

        $today = Carbon::now();

        foreach ($salesOrders as $order) {
            // Ambil tanggal terakhir PO receive dan Delivery order
            $poRcvDate = PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
            $postingDate = DeliveryOrder::where('OrderNo', $order->SO)->latest('DeliveryDate')->value('DeliveryDate');
            $orderDate = $order->OrderDate ? Carbon::parse($order->OrderDate) : null;

            // Hitung keterlambatan
            $delayedDeliveryToCust = null;

            if ($poRcvDate && $postingDate) {
                $delayedDeliveryToCust = Carbon::parse($poRcvDate)->diffInDays(Carbon::parse($postingDate));
            } elseif ($poRcvDate && !$postingDate) {
                // belum delivered -> hitung dari tanggal poRcv sampai hari ini
                $delayedDeliveryToCust = Carbon::parse($poRcvDate)->diffInDays($today);
                $notDeliveredCount++;  // Hitung juga sebagai belum delivered
            } elseif (!$poRcvDate && $postingDate && $orderDate) {
                $delayedDeliveryToCust = Carbon::parse($orderDate)->diffInDays(Carbon::parse($postingDate));
            } elseif (!$postingDate ) {
                // Jika tidak ada postingDate (belum delivered)
                $notDeliveredCount++;
            }

            if (is_numeric($delayedDeliveryToCust)) {
                if ($delayedDeliveryToCust > 7) {
                    $delayedMoreThan7Days++;
                }
            }
        }

        return view('dashboard', compact('notDeliveredCount', 'delayedMoreThan7Days'));
    }
    public function showNotifikasi(Request $request)
{
    $type = $request->query('type');

    $salesOrders = SalesOrder::all();
    $today = Carbon::now();

    $filteredOrders = collect();

    foreach ($salesOrders as $order) {
        $poRcvDate = PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
        $postingDate = DeliveryOrder::where('OrderNo', $order->SO)->latest('DeliveryDate')->value('DeliveryDate');
        $orderDate = $order->OrderDate ? Carbon::parse($order->OrderDate) : null;

        $delayedDeliveryToCust = null;

        if ($poRcvDate && $postingDate) {
            $delayedDeliveryToCust = Carbon::parse($poRcvDate)->diffInDays(Carbon::parse($postingDate));
        } elseif ($poRcvDate && !$postingDate) {
            $delayedDeliveryToCust = Carbon::parse($poRcvDate)->diffInDays($today);
        } elseif (!$poRcvDate && $postingDate && $orderDate) {
            $delayedDeliveryToCust = Carbon::parse($orderDate)->diffInDays(Carbon::parse($postingDate));
        }

       if ($type == 'notdelivered' && !$postingDate) {
    $filteredOrders->push($order);
}

if ($type == 'delayed' && is_numeric($delayedDeliveryToCust) && $delayedDeliveryToCust >= 7 && $delayedDeliveryToCust <= 14 && !$postingDate) {
    $filteredOrders->push($order);
}

if ($type == 'delay' && is_numeric($delayedDeliveryToCust) && $delayedDeliveryToCust > 14 && !$postingDate) {
    $filteredOrders->push($order);
}

    }

    return view('notifikasi.detail', compact('filteredOrders', 'type'));
}

}
