<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoRcv extends Model
{
    protected $table = 'po_rcv';

    protected $fillable = [
        'po_no',
        'rcv_no',
        'posting_date',
        'item_no',
        'description',
        'quantity',
        'uom',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'posting_date' => 'datetime',
    ];

    /**
     * Relasi ke SalesOrder: berdasarkan PO (sales_orders.PO -> po_rcv.po_no)
     * Jika 1 PO bisa digunakan di banyak sales_orders, maka gunakan hasMany
     */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'PO', 'po_no');
    }
}
