<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalesOrder extends Model
{
    protected $table = 'sales_orders';

    protected $fillable = [
        'SO', 'CustPO', 'Customer', 'ShipTo', 'CompletelyShipped',
        'OrderDate', 'DocDate', 'PostingDate', 'SalesPerson',
        'No_', 'PartNo', 'Description', 'InfoItem', 'Qty', 'UOM',
        'OutstandingQty', 'UnitPrice', 'Disc', 'TotalAmount',
        'PR', 'PO', 'Notes', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'OrderDate'   => 'datetime',
        'DocDate'     => 'datetime',
        'PostingDate' => 'datetime',
        // Jangan masukkan DeliveryDate dan CustomerDueDate jika bukan kolom di tabel ini
    ];

    /**
     * Relasi ke model PoRcv berdasarkan PO -> po_no
     * Bisa banyak PoRcv, karena bisa ada multiple posting_date
     */
    public function poRcv()
    {
        return $this->hasMany(PoRcv::class, 'po_no', 'PO');
    }

    /**
     * Ambil PoRcv terbaru berdasarkan posting_date
     */
    public function latestPoRcv()
    {
        return $this->poRcv()->latest('posting_date')->first();
    }

    /**
     * Relasi ke model DeliveryOrder berdasarkan SO -> OrderNo
     * Bisa banyak DeliveryOrder
     */
    public function deliveryOrder()
    {
        return $this->hasMany(DeliveryOrder::class, 'OrderNo', 'SO');
    }

    /**
     * Ambil DeliveryOrder terbaru berdasarkan DeliveryDate
     */
    public function latestDeliveryOrder()
    {
        return $this->deliveryOrder()->latest('DeliveryDate')->first();
    }

    /**
     * Accessor untuk tanggal posting terakhir PoRcv (Carbon instance atau null)
     */
    public function getPoRcvDateAttribute()
    {
        $poRcv = $this->latestPoRcv();
        return $poRcv ? Carbon::parse($poRcv->posting_date) : null;
    }

    /**
     * Accessor untuk tanggal pengiriman terakhir (Carbon instance atau null)
     */
    public function getLatestDeliveryDateAttribute()
    {
        $delivery = $this->latestDeliveryOrder();
        return $delivery ? Carbon::parse($delivery->DeliveryDate) : null;
    }

    /**
     * Hitung keterlambatan antara OrderDate ke PoRcvDate (hari)
     * Jika belum ada poRcv dan status Indent, hitung dari OrderDate ke hari ini
     */
 public function getDelayedSoToRcvAttribute()
{
    $poRcvDate = $this->po_rcv_date;
    $orderDate = $this->OrderDate instanceof Carbon ? $this->OrderDate : Carbon::parse($this->OrderDate);
    $today = Carbon::now();

    if ($poRcvDate) {
        return $orderDate->diffInDays($poRcvDate);
    }

    // Kalau tidak ada poRcvDate, hitung delay dari orderDate ke hari ini
    return $orderDate->diffInDays($today);
}


    /**
     * Hitung keterlambatan dari PoRcvDate ke hari ini,
     * atau jika tidak ada PoRcvDate tapi ada pengiriman, hitung dari OrderDate ke DeliveryDate
     */
    public function getDelayedDeliveryToCustAttribute()
    {
        $poRcvDate = $this->po_rcv_date;
        $postingDate = $this->latest_delivery_date;
        $orderDate = $this->OrderDate instanceof Carbon ? $this->OrderDate : Carbon::parse($this->OrderDate);
        $today = Carbon::now();

        if ($poRcvDate) {
            return $poRcvDate->diffInDays($today);
        }

        if ($postingDate) {
            return $orderDate->diffInDays($postingDate);
        }

        return null;
    }

    /**
     * Status pengiriman berdasarkan CompletelyShipped dan ada tidaknya pengiriman
     */
    public function getDeliveredStatusAttribute()
    {
        $postingDate = $this->latest_delivery_date;

        if ($this->CompletelyShipped || $postingDate) {
            return 'Delivered';
        }

        return $this->po_rcv_date ? 'Ready to Ship' : 'Indent';
    }

    /**
     * Scope untuk filter berdasarkan customer, SO, CustPO, year, dan delivered_status
     * $filters = [
     *    'customer' => '...',
     *    'SO' => '...',
     *    'CustPO' => '...',
     *    'year' => 2023,
     *    'delivered_status' => 'Delivered'|'Ready to Ship'|'Indent',
     * ]
     */
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['customer'])) {
            $query->where('Customer', 'like', '%' . $filters['customer'] . '%');
        }

        if (!empty($filters['SO'])) {
            $query->where('SO', 'like', '%' . $filters['SO'] . '%');
        }

        if (!empty($filters['CustPO'])) {
            $query->where('CustPO', 'like', '%' . $filters['CustPO'] . '%');
        }

        if (!empty($filters['year'])) {
            $query->whereYear('OrderDate', $filters['year']);
        }

        if (isset($filters['delivered_status'])) {
            $status = $filters['delivered_status'];

            if ($status === 'Delivered') {
                // Delivered: CompletelyShipped = true OR deliveryOrder exists
                $query->where(function ($q) {
                    $q->where('CompletelyShipped', true)
                      ->orWhereHas('deliveryOrder');
                });
            } elseif ($status === 'Ready to Ship') {
                // Ready to Ship: poRcv exists AND not delivered
                $query->whereHas('poRcv')
                      ->where('CompletelyShipped', false)
                      ->whereDoesntHave('deliveryOrder');
            } elseif ($status === 'Indent') {
                // Indent: no poRcv AND no deliveryOrder AND not CompletelyShipped
                $query->whereDoesntHave('poRcv')
                      ->where('CompletelyShipped', false)
                      ->whereDoesntHave('deliveryOrder');
            }
        }

        return $query;
    }
    public function scopeNotDelivered($query)
{
    return $query->where('delivered', false);
}

public function scopeDelayed($query, $days = 7)
{
    return $query->notDelivered()->where('created_at', '<', now()->subDays($days));
}

}
