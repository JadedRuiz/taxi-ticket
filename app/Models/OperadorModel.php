<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperadorModel extends Model
{
    protected $table= 'tbl_operadores';

    protected $primaryKey='id_operador';

    public $incrementing = false;

    protected $fillable= [
        'id_direccion', 
        'nombres', 
        'apellidos', 
        'correo', 
        'telefono', 
        'status', 
        'dtCreacion', 
        'activo'
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
