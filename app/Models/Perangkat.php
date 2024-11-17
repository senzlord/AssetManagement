<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perangkat extends Model
{
    use HasFactory,SoftDeletes;

    // Specify the table name explicitly
    protected $table = 'perangkat';

    // Set the primary key column
    protected $primaryKey = 'PERANGKAT_ID';

    // Disable auto-incrementing for the primary key if it is not an integer
    public $incrementing = true;

    // Specify the primary key type if it's not the default 'int'
    protected $keyType = 'int';

    // Allow mass assignment on these fields
    protected $fillable = [
        'HOST_NAME', 'TYPE', 'SERIAL_NUMBER', 'IP_ADDRESS', 'LOCATION',
        'LICENCE_END_DATE', 'PRODUCT_ID_DEVICE', 'JUMLAH_SFP_DICABUT', 'STOCK', 
        'CATEGORY', 'VENDOR', 'TANGGAL_CABUT_SFP', 'BRAND', 'EOS_HARDWARE', 
        'EOS_HARDWARE_RISK', 'FIRMWARE', 'EOS_FIRMWARE', 'EOS_FIRMWARE_RISK', 
        'LICENSE_END_RISK', 'USER', 'NO_ASSET', 'NAMA_KONTRAK', 'NO_KONTRAK', 
        'STATUS_SUPPORT', 'ATS_END_DATE', 'PIC', 'OS_VERSION', 'SFP'
    ];

    // Specify date fields to be automatically cast to Carbon instances
    protected $dates = [
        'LICENCE_END_DATE', 'EOS_HARDWARE', 'EOS_FIRMWARE', 'ATS_END_DATE',
    ];

    protected $casts = [
        'EOS_HARDWARE' => 'datetime',
        'EOS_FIRMWARE' => 'datetime',
        'LICENCE_END_DATE' => 'datetime',
        'ATS_END_DATE' => 'datetime',
    ];

    /**
     * Scope a query to only include SFP type perangkat.
     */
    public function scopeSfp($query)
    {
        return $query->where('TYPE', 'SFP');
    }

    /**
     * Scope a query to only include Hardware type perangkat.
     */
    public function scopeHardware($query)
    {
        return $query->where('TYPE', 'Hardware');
    }

    /**
     * Scope a query to only include Non-Hardware type perangkat.
     */
    public function scopeNonHardware($query)
    {
        return $query->where('TYPE', 'Non-Hardware');
    }
}
