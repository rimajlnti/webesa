<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\DeliveryOrder;
use Illuminate\Support\Facades\DB;


class SalesOrderUtilityController extends Controller
{
    public function activeCustomers()
    {
        $activeCustomers = SalesOrder::select('Customer')->distinct()->get();
        return view('sales_orders.active_customers', compact('activeCustomers'));
    }

   public function pendingOrders()
{
    // Ambil semua SO yang belum memiliki pengiriman (DeliveryDate masih NULL)
    $pendingOrders = DB::table('sales_orders as so')
        ->leftJoin('delivery_orders as do', 'so.SO', '=', 'do.OrderNo')
        ->whereNull('do.DeliveryDate')
        ->select('so.*')
        ->distinct()
        ->get();

    return view('sales_orders.pending_orders', compact('pendingOrders'));
}



    public function getOrdersByCustomer($customer)
    {
        $salesOrders = SalesOrder::where('Customer', $customer)->get();
        return response()->json($salesOrders);
    }
}
