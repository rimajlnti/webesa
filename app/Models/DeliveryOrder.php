<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasFactory;

    protected $table = 'delivery_orders';

    protected $fillable = [
        'OrderNo',
        'DeliveryDate',
        // tambahkan kolom lain sesuai kebutuhan tabel
    ];

    protected $casts = [
        'DeliveryDate' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Relasi ke SalesOrder: berdasarkan SO (sales_orders.SO -> delivery_orders.OrderNo)
     * Satu delivery order hanya terkait dengan satu sales order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'OrderNo', 'SO');
    }
}
