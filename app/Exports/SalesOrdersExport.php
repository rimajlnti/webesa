<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class SalesOrdersExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle,  WithEvents
{
    protected $salesOrders;

    public function __construct(array $salesOrders)
    {
        $this->salesOrders = $salesOrders;
    }

    public function array(): array
    {
        return $this->salesOrders;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sales Order No',
            'Customer PO',
            'Customer Name',
            'Ship To',
            'Part Number',
            'Description',
            'Outstanding Quantity',
            'Order Date',
            'RCV Warehouse Date',
            'Delivery Date',
            'Delay SO to RCV WH (days)',
            'Delay Delivery to Customer (days)',
            'Delivery Status',
            'Total Amount',
            'Sales Person',
            'Notes',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style diterapkan di AfterSheet
    }

    public function title(): string
    {
        return 'Sales Orders';
    }

//     public function drawings()
// {
//     $drawing = new Drawing();
//     $drawing->setName('Company Logo');
//     $drawing->setDescription('Company Logo');
//     $drawing->setPath(public_path('sbadmin/img/logo_esa.png'));
//     $drawing->setHeight(70);
//     $drawing->setCoordinates('A1'); // Tetap di A1
//     $drawing->setOffsetY(5);
//     return $drawing;
// }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sisipkan baris agar logo tidak menimpa tabel
                $sheet->insertNewRowBefore(1, 5);

                // Judul Laporan (baris ke-3)
                $sheet->mergeCells('A3:Q3');
                $sheet->setCellValue('A3', 'Sales Order Report');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Tanggal ekspor (baris ke-4)
                $tanggal = Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue('A4', 'Tanggal Ekspor: ' . $tanggal);

                // Style header di baris 6
                $headerRange = 'A6:Q6';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9E1F2'], // biru muda
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                // Border seluruh data
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle('A6:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'alignment' => ['vertical' => 'center'],
                ]);
            },
        ];
    }
}
