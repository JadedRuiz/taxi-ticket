<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\ViajeModel as Viaje;
use App\Models\DetViajeModel as DetViaje;
use App\Models\DestinoModel as Destino;
use App\Exports\TicketExport as Ticket;
use App\Events\ActualizarViaje;
use Exception;

class ViajeController extends Controller
{
    function index() {
        return view('Viaje');
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

    public function obtenerViajeId(Request $request) {
        $viaje = DB::table("tbl_viajes as tblV")
        ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio","tblV.status", "tblO.nombres","tblO.apellidos","dtV.tipo_pago",
        "tblV.caja_id")
        ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
        ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
        ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
        ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
        ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
        ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
        ->where('id_viaje',$request->id_viaje)
        ->first();
        if ($viaje) {
            return ["ok" => true, "data" => $viaje];
        }
        return ["ok" => false, "message" => "No fue posible recuperar el viaje con ID: {$request->id_viaje}"];
    }

    static function refescarViajeSocket($id_viaje) {
        $reservacion = DB::table("tbl_viajes as tblV")
        ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio","tblV.status", "tblO.nombres","tblO.apellidos","dtV.tipo_pago","tblV.caja_id")
        ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
        ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
        ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
        ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
        ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
        ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
        ->where('id_viaje',$id_viaje)
        ->first();
        if ($reservacion) {
            broadcast(new ActualizarViaje([
                "id_viaje" => $id_viaje,
                "row_caja" => view('components.tables.table_fila_viaje', [
                    'reservacion' => $reservacion,
                    'user' => 'Cajera'
                ])->render(),
                "row_operador" => view('components.tables.table_fila_viaje', [
                    'reservacion' => $reservacion,
                    'user' => 'Operador'
                ])->render(),
                "row_admin" => view('components.tables.table_fila_viaje', [
                    'reservacion' => $reservacion,
                    'user' => 'Administrador'
                ])->render()
            ]));
        } else {
            throw new Exception("No fue posible recuperar el viaje con ID: {$id_viaje}", 500);
        }
    }

    function viajeMiTaxi(Request $res) {
        try{
            DB::beginTransaction();
            $folio = "FV-".date('Ymdhms');

            //Insertamos el viaje
            $viaje = Viaje::create([
                "empresa_id" => 1,
                "folio" => $folio,
                "nombre_viaje" => "Viaje Reservado",
                "status" => "Pendiente", //Pendiente
                "tipo_servicio" => "TAXI SEGURO ADO",
                "tipo_viaje" => "Viaje Sencillo",
                "date_creacion" => date('Y-m-d h:m:s'),
                "comentarios" => "Sin comentarios"
            ]);

            $det_viaje= DetViaje::create([
                "viaje_id" => $viaje->id_viaje,
                "origen_id" => $res["iIdOrigen"],
                "destino_id" => $res["iIdDestino"],
                "vehiculo" => "",
                "no_maletas" => 4,
                "no_pasajeros" => 4,
                "nombre" => $res["sNombre"],
                "correo" => $res["sCorreo"],
                "telefono" => $res["sTelefono"],
                "tipo_pago" => "Efectivo"
            ]);

            DB::commit();

            //Enviar correo al ciudadano
            $destino= Destino::select('destino','precio','distancia','duracion')->where('id_destino',$res["iIdDestino"])->first();
            $origen= DB::table('tbl_origenes')->select('origen')->where('id_origen',$res["iIdOrigen"])->first();

            Mail::send('plantillas/ticket_correo', compact('viaje','det_viaje',"destino","origen"), function ($message) use ($res){
                $message->subject('Reservas - Mi taxi');
                $message->to($res["sCorreo"],$res["sNombre"]);     
            });

            return ['ok' => true, "data" => "Registro Exitoso"];

        } catch(\Exception | \PDOException $e){
            DB::rollBack();
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    function reservaExitosa() {
        return view('reserva_exitosa');
    }

    public function editarViaje(Request $request) {
        try {
            DB::beginTransaction();
            if($request->date_creacion == "") {
                return ['ok' => false, "data" => "La fecha del viaje es obligatorio"];
            }
            $fecha = date("Y-m-d H:i:s", strtotime($request->date_creacion));
            DB::table("tbl_viajes")
            ->where("id_viaje",$request->id_viaje)
            ->update([
                "caja_id" => $request->caja_id,
                "date_creacion" => $fecha
            ]);
            DB::table("det_viaje")
            ->where("viaje_id",$request->id_viaje)
            ->update([
                "nombre" => $request->nombre,
                "correo" => $request->correo,
                "telefono" => $request->telefono,
                "tipo_pago" => $request->tipo_pago,
                "precio_viaje" => $request->precio_viaje
            ]);
            DB::commit();
            return ['ok' => true, "data" => "Viaje Editado"];
        } catch(\Exception | \PDOException $e){
            DB::rollBack();
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    public function migrar() {
        set_time_limit(220); // Aumenta el tiempo máximo si es necesario
    
        $viajes = DB::connection('mysql2')
            ->table('wpjy_posts')
            ->select("ID", "wpp.meta_value", "post_date")
            ->join("wpjy_postmeta as wpp", "wpp.post_id", "=", "ID")
            ->whereBetween("post_date", ['2024-12-27', '2025-01-01'])
            ->where("meta_key", "chbs_price_fixed_value")
            ->orderBy("post_date", "ASC")
            ->get();
    
        if ($viajes->isEmpty()) {
            return "No hay viajes para migrar.";
        }
    
        DB::connection('mysql2')
            ->table('wpjy_posts')
            ->select("ID", "wpp.meta_value", "post_date")
            ->join("wpjy_postmeta as wpp", "wpp.post_id", "=", "ID")
            ->whereBetween("post_date", ['2024-12-27', '2025-01-01'])
            ->where("meta_key", "chbs_price_fixed_value")
            ->orderBy("post_date", "ASC")
            ->chunk(100, function ($viajes) {
                foreach ($viajes as $viaje) {
                    Viaje::join("det_viaje as dv", "dv.viaje_id", "=", "id_viaje")
                        ->where("folio", $viaje->ID)
                        ->whereNull('dv.precio_viaje') // Usa `whereNull` para comparar con `null`
                        ->update([
                            "dv.precio_viaje" => $viaje->meta_value,
                        ]);
                }
            });
    
        return "Migración completada exitosamente.";
    }
    
}
