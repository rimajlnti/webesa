<?php

namespace App\Http\Controllers;

use App\Exports\SalesOrdersExport;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SalesOrderExportController extends Controller
{
    public function export()
    {
        $today = now();

        $query = SalesOrder::query();

        if (request('customer')) {
            $query->where('Customer', 'like', '%' . request('customer') . '%');
        }

        if (request('SO')) {
            $query->where('SO', 'like', '%' . request('SO') . '%');
        }

        if (request('CustPO')) {
            $query->where('CustPO', 'like', '%' . request('CustPO') . '%');
        }

        $salesOrders = $query->get()
            ->map(function ($order) use ($today) {
                $poRcvDate = \App\Models\PoRcv::where('po_no', $order->PO)
                    ->latest('posting_date')
                    ->value('posting_date');

                $postingDate = \App\Models\DeliveryOrder::where('OrderNo', $order->SO)
                    ->latest('DeliveryDate')
                    ->value('DeliveryDate');

                $orderDate = $order->OrderDate ? Carbon::parse($order->OrderDate) : null;

                // Format RCV WH Date
                $rcvFormattedDate = $this->formatRcvDate($poRcvDate, $postingDate, $orderDate);

                // Hitung Delayed SO to RCV Warehouse
                $delayedSoToRcv = '-';
                if ($orderDate) {
                    if ($poRcvDate) {
                        $rcvDate = Carbon::parse($poRcvDate)->startOfDay();
                        $delayedSoToRcv = $orderDate->copy()->startOfDay()->diffInDays($rcvDate);
                    } elseif ($postingDate && !$poRcvDate) {
                        $delayedSoToRcv = '-';
                    } else {
                        $delayedSoToRcv = $orderDate->copy()->startOfDay()->diffInDays($today->copy()->startOfDay());
                    }
                }

                // Hitung Delayed Delivery to Customer
                $delayedDeliveryToCust = '-';
                if ($poRcvDate && $postingDate) {
                    $delayedDeliveryToCust = Carbon::parse($poRcvDate)
                        ->diffInDays(Carbon::parse($postingDate));
                } elseif ($poRcvDate && !$postingDate) {
                    $delayedDeliveryToCust = Carbon::parse($poRcvDate)
                        ->diffInDays($today);
                } elseif (!$poRcvDate && $postingDate && $orderDate) {
                    $delayedDeliveryToCust = $orderDate
                        ->diffInDays(Carbon::parse($postingDate));
                }

                // Status pengiriman
                $deliveredStatus = $order->CompletelyShipped || $postingDate ? 'Delivered' :
                    ($poRcvDate ? 'Ready to Ship' : 'Indent');

                return [
                    $order->id,
                    $order->SO,
                    $order->CustPO,
                    $order->Customer,
                    $order->ShipTo,
                    $order->PartNo,
                    $order->Description,
                    $order->OutstandingQty,
                    $orderDate ? $orderDate->format('Y-m-d') : '-', // ← Order Date ditampilkan
                    $rcvFormattedDate,
                    $postingDate ? Carbon::parse($postingDate)->format('Y-m-d') : '-',
                    $delayedSoToRcv,
                    $delayedDeliveryToCust,
                    $deliveredStatus,
                    $order->TotalAmount ?? 0,
                    $order->SalesPerson,
                    $order->Notes,
                ];
            })->toArray();

        return Excel::download(new SalesOrdersExport($salesOrders), 'sales_orders.xlsx');
    }

    /**
     * Menentukan tanggal RCV WH berdasarkan prioritas:
     * 1. posting_date dari tabel PoRcv
     * 2. order date jika posting date tidak ada namun postingDate ada
     * 3. '-'
     */
    private function formatRcvDate($poRcvDate, $postingDate, $orderDate)
    {
        if ($poRcvDate) {
            return Carbon::parse($poRcvDate)->format('Y-m-d');
        } elseif ($postingDate && !$poRcvDate && $orderDate) {
            return Carbon::parse($orderDate)->format('Y-m-d');
        } else {
            return '-';
        }
    }
}
