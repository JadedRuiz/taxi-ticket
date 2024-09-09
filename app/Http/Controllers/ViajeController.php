<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViajeModel as Viaje;

class ViajeController extends Controller
{
    function index() {
        return view('viaje');
    }

    function create(Request $res) {
        try{
            Viaje::create([
                'iIdDirOrigen' => 1,
                'iIdDirDest' => 1,
                'sNombre' => $res["sNombre"], 
                'sTelefono' => $res['sTelefono'], 
                'sCorreo' => $res["sCorreo"], 
                'iStatus' => 1, 
                'iTipo' => 1,
                'dtCreacion' => date('Y-m-d')
            ]);

            return ['ok' => true, "data" => "Registro Exitoso"];
        }catch(\Exception $e){
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }
}
