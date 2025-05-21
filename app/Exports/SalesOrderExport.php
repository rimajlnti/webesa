<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SalesOrderExport implements 
    FromArray,
    WithHeadings,
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'SO',
            'PO No',
            'Nama Cust',
            'Ship-to Code',
            'Part No.',
            'Description',
            'Outstanding Qty',
            'Receiving From Warehouse',
            'Posting Date DO',
            'Delayed SO to RCV Warehouse (Days)',
            'Delayed Delivery to Cust (Days)',
            'Delivered Status',
            'Outstanding Amount',
            'Sales Person',
            'Notes',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
