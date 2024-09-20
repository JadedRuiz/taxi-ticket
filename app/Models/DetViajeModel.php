<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetViajeModel extends Model
{
    protected $table= 'det_viaje';

    protected $primaryKey='id_det_viaje';

    public $incrementing = false;

    protected $fillable= [
        'viaje_id', 
        'origen_id', 
        'destino_id', 
        'vehiculo', 
        'no_maletas', 
        'no_pasajeros', 
        'nombre', 
        'correo', 
        'telefono', 
        'tipo_pago'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
