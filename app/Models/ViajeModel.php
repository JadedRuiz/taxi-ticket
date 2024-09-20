<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViajeModel extends Model
{
    protected $table= 'tbl_viajes';

    protected $primaryKey='id_viaje';

    public $incrementing = true;

    protected $fillable= [
        'empresa_id',
        'folio',
        'nombre_viaje',
        'status', 
        'tipo_servicio', 
        'tipo_viaje', 
        'date_creacion',
        'comentarios'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
