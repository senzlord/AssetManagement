<?php

namespace App\Exports;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\FromCollection;

class SfpExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Perangkat::sfp()->get();
    }
}
