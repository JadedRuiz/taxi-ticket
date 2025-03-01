<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoModel extends Model
{
    protected $table= 'tbl_gastos';

    protected $primaryKey='id_gasto';

    public $incrementing = true;

    protected $fillable= [
        'concepto_gasto', 
        'folio', 
        'fecha', 
        'status', 
        'importe', 
        'descripcion', 
        'tipo_comprobante', 
        'folio_comprobante', 
        'recibe_efectivo', 
        'admin_gastos_id', 
        'fecha_creacion', 
        'usuario_creacion'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}