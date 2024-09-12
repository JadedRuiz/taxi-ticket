<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViajeModel as Viaje;
use App\Models\DetViajeModel as DetViaje;
use App\Models\DestinoModel as Destino;
use Illuminate\Support\Facades\DB;

class ViajeController extends Controller
{
    function index() {
        return view('viaje');
    }

    function obtenerOrigen($id) {
        try {

            $origen= DB::table("tbl_origenes")->select("id_origen as iIdOrigen",'origen as sOrigen')
            ->where("id_origen",$id)
            ->first();
            return [ 'ok' => true, "data" => $origen ];

        } catch(\Exception | \PDOException $e){
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
    }

    function viajeMiTaxi(Request $res) {
        try{
            DB::beginTransaction();
            $folio = "FV-".date('Ymdhms');

            //Insertamos el viaje
            $viaje = Viaje::create([
                "folio" => $folio,
                "nombre_viaje" => "Viaje Reservado",
                "status" => 1, //Pendiente
                "tipo_servicio" => "Mi Taxi",
                "tipo_viaje" => "Viaje Sencillo",
                "date_creacion" => date('Y-m-d h:m:s'),
                "comentarios" => "VIAJE MI TAXI"
            ]);

            DetViaje::create([
                "viaje_id" => $viaje->id_viaje,
                "origen" => $res["iIdOrigen"],
                "destino" => $res["iIdDestino"],
                "vehiculo" => "",
                "no_maletas" => 4,
                "no_pasajeros" => 4,
                "nombre" => $res["sNombre"],
                "correo" => $res["sCorreo"],
                "telefono" => $res["sTelefono"],
                "tipo_pago" => "Efectivo"
            ]);

            DB::commit();

            return ['ok' => true, "data" => "Registro Exitoso"];

        } catch(\Exception | \PDOException $e){
            DB::rollBack();
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    function reservaExitosa() {
        return view('reserva_exitosa');
    }
}
