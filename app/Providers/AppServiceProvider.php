<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SalesOrder;
use App\Models\PoRcv;
use App\Models\DeliveryOrder;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $notDeliveredCount = 0;
            $delayedMoreThan1Days = 0;
            $delayedMoreThan7Days = 0;
            $delayedMoreThan14Days = 0;


            $salesOrders = SalesOrder::all();
            $today = Carbon::now();

            foreach ($salesOrders as $order) {
                $poRcvDate = PoRcv::where('po_no', $order->PO)->latest('posting_date')->value('posting_date');
                $postingDate = DeliveryOrder::where('OrderNo', $order->SO)->latest('DeliveryDate')->value('DeliveryDate');

                if (!$postingDate) {
                    $notDeliveredCount++;
                    if ($poRcvDate) {
                        $delayeddata = Carbon::parse($poRcvDate)->diffInDays($today);

                        if ($delayeddata >= 1 && $delayeddata <= 6) {
                            $delayedMoreThan1Days++;
                        } 
                    }
                    if ($poRcvDate) {
                        $delayed = Carbon::parse($poRcvDate)->diffInDays($today);

                        if ($delayed >= 7 && $delayed <= 14) {
                            $delayedMoreThan7Days++;
                        } 
                    }
                    if ($poRcvDate) {
                        $delay = Carbon::parse($poRcvDate)->diffInDays($today);
                        if ($delay > 14) {
                            $delayedMoreThan14Days++;
                        } 
                    }
                }
            }

            // Kirim data ke semua view
            $view->with('notDeliveredCount', $notDeliveredCount);
            $view->with('delayedMoreThan7Days', $delayedMoreThan1Days);
            $view->with('delayedMoreThan7Days', $delayedMoreThan7Days);
            $view->with('delayedMoreThan14Days', $delayedMoreThan14Days);
        });
    }
}
