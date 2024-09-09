<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViajeModel extends Model
{
    use HasFactory;

    protected $table= 'tbl_viajes';

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable= [
        'iIdDirOrigen',
        'iIdDirDest',
        'sNombre', 
        'sTelefono', 
        'sCorreo', 
        'iStatus', 
        'iTipo',
        'dtCreacion'
    ];
}
