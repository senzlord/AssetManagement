<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'kategori';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Allow mass assignment on these fields
    protected $fillable = [
        'name', // Nama Kategori
        'type', // Hardware or Non-Hardware
    ];

    // Set the type field to enum constraints for validation
    public static function types()
    {
        return ['Hardware', 'Non-Hardware'];
    }
}