<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinoModel as Destino;
use Illuminate\Support\Facades\DB;

class DestinoController extends Controller
{
    function index() {
        try {
            
            $destinos = Destino::select('id_destino as iIdDestino', 'destino as sNombre',DB::raw("CONCAT('Calle ',tblD.calle,' ',tblD.num_ext,', ',tblD.colonia,', ',tblD.cp,' ',tblD.ciudad) as sDireccion"))
            ->leftJoin("tbl_direcciones as tblD","tblD.id_direccion","=","tbl_destinos.direccion_id")
            ->get();
            return [ 'ok' => true, "data" => $destinos ];
            
        } catch(\Exception | \PDOException $e){
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
    }

    function obtenerDestinoId($id) {
        try {

            $destino= Destino::select("destino as sDestino",'precio as sPrecio')
            ->where("id_destino",$id)
            ->first();
            $destino->sPrecio = "$ ".number_format($destino->sPrecio,2);
            return [ 'ok' => true, "data" => $destino ];

        } catch(\Exception | \PDOException $e){
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
    }
}
