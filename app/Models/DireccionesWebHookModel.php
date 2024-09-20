<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionesWebHookModel extends Model
{
    protected $table= 'tbl_direcciones_webhook';

    protected $primaryKey='id_direccion';

    public $incrementing = true;

    protected $fillable= [
        'empresa_id', 
        'nombre', 
        'direccion', 
        'duracion', 
        'distancia', 
        'precio',
        'tipo'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
