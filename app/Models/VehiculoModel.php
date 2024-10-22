<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoModel extends Model
{
    protected $table= 'tbl_vehiculos';

    protected $primaryKey='id_vehiculo';

    public $incrementing = false;

    protected $fillable= [
        'fotografia_id',
        'vehiculo', 
        'marca', 
        'modelo', 
        'placa', 
        'no_serie', 
        'aseguradora', 
        'poliza', 
        'dtFinPoliza', 
        'notas', 
        'dtCreacion', 
        'activo'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
