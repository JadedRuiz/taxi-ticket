<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinoModel extends Model
{
    protected $table= 'tbl_destinos';

    protected $primaryKey='id_destino';

    public $incrementing = false;

    protected $fillable= [
        'direccion_id', 
        'destino', 
        'precio', 
        'distancia', 
        'duracion', 
        'date_creacion', 
        'activo'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
