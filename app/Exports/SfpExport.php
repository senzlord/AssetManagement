<?php

namespace App\Exports;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class SfpExport implements FromQuery, WithHeadings, WithCustomStartCell, WithEvents
{
    /**
     * Fetch the query for export.
     */
    public function query()
    {
        return Perangkat::sfp()->select(
            'PERANGKAT_ID',
            'LOCATION',
            'VENDOR',
            'PRODUCT_ID_DEVICE',
            'SERIAL_NUMBER',
            'HOST_NAME',
            'IP_ADDRESS',
            'JUMLAH_SFP_DICABUT'
        );
    }

    /**
     * Define the column headings.
     */
    public function headings(): array
    {
        return [
            'ID Perangkat',
            'Location',
            'Vendor',
            'Product ID Device',
            'Serial Number Device',
            'Hostname',
            'IP Address',
            'Jumlah SFP Dicabut',
        ];
    }

    /**
     * Set the starting cell for the data.
     */
    public function startCell(): string
    {
        return 'A2'; // Start the data from row 2
    }

    /**
     * Customize the sheet with a title row.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Add a title row
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'SFP Device Data Export');

                // Style the title
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Style the headings row (row 2)
                $event->sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Adjust column widths
                foreach (range('A', 'H') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
