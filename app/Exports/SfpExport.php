<?php

namespace App\Exports;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class SfpExport implements FromQuery, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    protected $maxSfpColumns = 9;
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
            'JUMLAH_SFP_DICABUT',
            'SFP'
        );
    }

    /**
     * Define the column headings.
     */
    public function headings(): array
    {
        // Base headings
        $headings = [
            'ID Perangkat',
            'Location',
            'Vendor',
            'Product ID Device',
            'Serial Number Device',
            'Hostname',
            'IP Address',
            'Jumlah SFP Dicabut',
        ];

        // Dynamically add SFP Product and Serial Number headings
        for ($i = 1; $i <= $this->maxSfpColumns; $i++) {
            $headings[] = "SFP Product ID $i";
            $headings[] = "SFP Serial Number $i";
        }

        return $headings;
    }

    /**
     * Map the data for each row.
     */
    public function map($perangkat): array
    {
        // Base data
        $row = [
            $perangkat->PERANGKAT_ID,
            $perangkat->LOCATION,
            $perangkat->VENDOR,
            $perangkat->PRODUCT_ID_DEVICE,
            $perangkat->SERIAL_NUMBER,
            $perangkat->HOST_NAME,
            $perangkat->IP_ADDRESS,
            $perangkat->JUMLAH_SFP_DICABUT,
        ];

        // Decode SFP JSON data
        $sfpData = json_decode($perangkat->SFP, true);

        // Add SFP Product and Serial Number columns
        for ($i = 0; $i < $this->maxSfpColumns; $i++) {
            $row[] = $sfpData['product_id'][$i] ?? ''; // Add Product ID or empty if not available
            $row[] = $sfpData['serial_number'][$i] ?? ''; // Add Serial Number or empty if not available
        }

        return $row;
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
                $event->sheet->mergeCells('A1:Z1');
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
                $event->sheet->getStyle('A2:Z2')->applyFromArray([
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
                foreach (range('A', 'Z') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
