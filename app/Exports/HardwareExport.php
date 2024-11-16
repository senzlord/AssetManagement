<?php

namespace App\Exports;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class HardwareExport implements FromQuery, WithHeadings, WithCustomStartCell, WithEvents
{
    /**
     * Fetch the query for export.
     */
    public function query()
    {
        return Perangkat::hardware()->select(
            'PERANGKAT_ID',
            'HOST_NAME',
            'BRAND',
            'CATEGORY',
            'SERIAL_NUMBER',
            'IP_ADDRESS',
            'LOCATION',
            'USER',
            'VENDOR',
            'EOS_HARDWARE',
            'FIRMWARE',
            'EOS_FIRMWARE',
            'LICENCE_END_DATE',
            'NAMA_KONTRAK',
            'NO_KONTRAK',
            'STATUS_SUPPORT',
            'ATS_END_DATE',
            'PIC'
        );
    }

    /**
     * Define the column headings.
     */
    public function headings(): array
    {
        return [
            'ID Perangkat',
            'Hostname',
            'Brand',
            'Kategori',
            'Serial Number',
            'IP Address',
            'Lokasi',
            'PIC/User',
            'Vendor',
            'End-of-Support Hardware',
            'Firmware Version',
            'End-of-Support Firmware',
            'License End Date',
            'Nama Kontrak',
            'No Kontrak',
            'Status Support',
            'ATS End Date',
            'PIC'
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
                $event->sheet->mergeCells('A1:R1');
                $event->sheet->setCellValue('A1', 'Hardware Device Data Export');

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
                $event->sheet->getStyle('A2:R2')->applyFromArray([
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
                foreach (range('A', 'R') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}