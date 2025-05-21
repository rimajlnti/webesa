<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
         $filters = $request->only(['customer', 'SO', 'CustPO', 'delivered_status']);

    $query = SalesOrder::filter($filters);
    
        if ($request->filled('start_date')) {
            $query->where(function ($q) use ($request) {
                $q->where('PostingDate', '>=', $request->start_date)
                  ->orWhereNull('PostingDate');
            });
        }
        
        if ($request->filled('end_date')) {
            $query->where(function ($q) use ($request) {
                $q->where('PostingDate', '<=', $request->end_date)
                  ->orWhereNull('PostingDate');
            });
        }

         $salesOrders = $query->with([
        'poRcv' => function ($q) {
            $q->latest('posting_date');
        },
        'deliveryOrder' => function ($q) {
            $q->latest('DeliveryDate');
        }
    ])->paginate(10);

    return view('sales_orders.index', compact('salesOrders'));
    }

   public function show($id)
{
    $order = SalesOrder::findOrFail($id);

    return view('sales_orders.show', [
        'order' => $order,
        'poRcvDate' => $order->po_rcv_date,
        'postingDate' => $order->latest_delivery_date,
        'delayedSoToRcv' => $order->delayed_so_to_rcv,
        'delayedDeliveryToCust' => $order->delayed_delivery_to_cust,
        'deliveredStatus' => $order->delivered_status,
    ]);
}




    public function update(Request $request, $id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->Notes = $request->input('Notes');
        $order->save();

        return redirect()->route('sales-orders.show', $order->id)
                         ->with('success', 'Catatan berhasil diperbarui.');
    }

    public function clearNotes($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->Notes = '';
        $order->save();

        return redirect()->route('sales-orders.show', $id)
                         ->with('success', 'Catatan berhasil dihapus.');
    }
    
    

}
