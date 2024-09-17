<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\ViajeModel as Viaje;
use App\Models\DetViajeModel as DetViaje;
use App\Models\DestinoModel as Destino;
use App\Exports\TicketExport as Ticket;

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
                "tipo_servicio" => "TAXI SEGURO ADO",
                "tipo_viaje" => "Viaje Sencillo",
                "date_creacion" => date('Y-m-d h:m:s'),
                "comentarios" => "Sin comentarios"
            ]);

            $det_viaje= DetViaje::create([
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

            //Enviar correo al ciudadano
            $destino= Destino::select('destino','precio','distancia','duracion')->where('id_destino',$res["iIdDestino"])->first();
            $origen= DB::table('tbl_origenes')->select('origen')->where('id_origen',$res["iIdOrigen"])->first();

            Mail::send('plantillas/ticket_correo', compact('viaje','det_viaje',"destino","origen"), function ($message) use ($res){
                $message->subject('Reservas - Mi taxi');
                $message->to($res["sCorreo"],$res["sNombre"]);     
            });

            //Generamos el PDF en B64
            try {
                $pdf_b64= Ticket::generarTicket($viaje, $det_viaje, $destino, $origen);
            }catch(\Exception $e) {
                return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
            }

            //Enviar Correo al interesado
            Mail::send('plantillas/ticket_correo', compact('viaje','det_viaje',"destino","origen"), function ($message) use ($res, $pdf_b64, $folio){
                $message->subject('Reserva Folio '.$folio.'| Mi taxi');
                $message->to(getenv('MAIL_ADMIN'), 'Administrador Plataforma'); 
                $message->attachData(base64_decode($pdf_b64),date('d/m/Y')."-Reserva|Mi taxi.pdf");    
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
}
