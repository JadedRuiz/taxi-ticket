<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Exports\TicketExport as Ticket;
use Illuminate\Support\Facades\Log;
use App\Models\DireccionesWebHookModel as Direcciones;
use App\Models\ViajeModel as Viaje;
use App\Models\DetViajeModel as DetViaje;

class AdminController extends Controller
{
    //
    function index() {
        if(session()->has('data-user')) {
            $user = json_decode($this->decode_json(session('data-user')));
            if($user->webhook){
                $reservaciones = DB::table("tbl_viajes as tblV")
                ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio")
                ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
                ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
                ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
                ->where("tblV.empresa_id",$user->id_empresa)
                ->orderBy("tblV.date_creacion",'DESC')
                ->get();
            }else{
                $reservaciones = DB::table("tbl_viajes as tblV")
                ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblD.destino","tblD.precio","tblO.origen","tblV.date_creacion",)
                ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
                ->leftJoin("tbl_destinos as tblD","tblD.id_destino","=","dtV.destino_id")
                ->leftJoin("tbl_origenes as tblO","tblO.id_origen","=","dtV.origen_id")
                ->where("tblV.empresa_id",$user->id_empresa)
                ->orderBy("tblV.date_creacion",'DESC')
                ->get();
            }
            $entries = ['public/js/admin.js'];
            return view('admin/Home', compact('user','reservaciones','entries'));
        }
        return view('admin/template/Login');
    }

    public function login(Request $body) {
        try {
            $user = DB::table("tbl_usuarios as tblU")
            ->select("id_usuario","usuario","nombre","password","tblE.id_empresa","tblE.empresa","tblE.logo_path","tblE.webhook")
            ->leftJoin("tbl_empresas as tblE","tblE.id_empresa","=","tblU.empresa_id")
            ->where("usuario",$body["usuario"])
            ->where("tblU.activo",1)
            ->first();

            if($user) {
                if($this->decode_json($user->password) == $body["pass"]) {
                    $menu = DB::table("rel_menu_usuario as rMU")
                    ->select("tblM.titulo", "tblM.icono", "tblM.ruta",)
                    ->join("tbl_menu as tblM","tblM.id_menu","=","rMU.menu_id")
                    ->where("tblM.activo",1)
                    ->where("rMU.usuario_id",$user->id_usuario)
                    ->get();
                    $user_data = [
                        "id_usuario" => $user->id_usuario,
                        "usuario" => $user->usuario,
                        "nombre" => $user->nombre,
                        "id_empresa" => $user->id_empresa,
                        "webhook" => $user->webhook,
                        "empresa" => $user->empresa,
                        "logo_path" => $user->logo_path,
                        "menu" => $menu
                    ];                    
                    Session::put("data-user",$this->encode_json(json_encode($user_data)));
                    return ["ok" => true, "data" => "Logueo Exitoso"];
                }
                return ["ok" => false, "message" => "E-AD-001 : Contraseña invalida"];
            }
            return ["ok" => false, "message" => "E-AD-001 : Usuario no encontrado"];
        } catch(\Exception | \PDOException $e) {
            return ["ok" => false, "message" => "E-AD-GEN : ".$e->getMessage()];
        }        
    }

    public function generarTicket(Request $res) {
        $viaje = DB::table("tbl_viajes")
        ->where("id_viaje",$res["id_viaje"])
        ->first();
        $det_viaje = DB::table("det_viaje")
        ->where("viaje_id",$viaje->id_viaje)
        ->first();
        $validar_empresa= DB::table("tbl_empresas")->where("id_empresa",$viaje->empresa_id)->first();
        if($validar_empresa && $validar_empresa->webhook) {
            $origen= Direcciones::select("nombre as origen")
            ->where("id_direccion",$det_viaje->origen_id)
            ->first();
            $destino= Direcciones::select("nombre as destino","precio","duracion","distancia")
            ->where("id_direccion",$det_viaje->destino_id)
            ->first();
        }else{
            $origen = DB::table("tbl_origenes")
            ->where("id_origen",$det_viaje->origen_id)
            ->first();
            $destino = DB::table("tbl_destinos")
            ->where("id_destino",$det_viaje->destino_id)
            ->first();
        }
        //Generamos el PDF en B64
        try {
            $pdf_b64= Ticket::generarTicket($viaje, $det_viaje, $destino, $origen, $validar_empresa);
            return ["ok"=> true, "data" => $pdf_b64];
        }catch(\Exception $e) {
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    public function logout() {
        session()->forget("data-user");
        return redirect()->route('admin.home');
    }

    public function encriptar(Request $body) {
        return $this->encode_json($body["password"]);
    }

    //WEBHOOK's
    public function webHookMyRide($id_empresa, Request $body) {

        try{
            DB::beginTransaction();

            //Insertamos el viaje
            $viaje = Viaje::create([
                "empresa_id" => $id_empresa,
                "folio" => $body["post"]["ID"],
                "nombre_viaje" => $body["post"]["post_title"],
                "status" => $body["booking_status_name"], //Pendiente
                "tipo_servicio" => $body["service_type_name"],
                "tipo_viaje" => $body["transfer_type_name"],
                "date_creacion" => $body["meta"]["pickup_datetime"],
                "comentarios" => $body["comment"]
            ]);
            //Insertamos las direcciones
            $origen = Direcciones::select("id_direccion")
            ->where("direccion",$this->Utf8_ansi($body["meta"]["coordinate"][0]["address"]))
            ->where("empresa_id",$id_empresa)
            ->first();
            if(!$origen){
                $origen = Direcciones::create([
                    'empresa_id' => $id_empresa, 
                    'nombre'=> strtoupper($this->Utf8_ansi($body["meta"]["coordinate"][0]["name"])), 
                    'direccion' => strtoupper($this->Utf8_ansi($body["meta"]["coordinate"][0]["address"])), 
                    'duracion' => "", 
                    'distancia' => "", 
                    'precio' => "",
                    'tipo' => "origen"
                ]);
            }
            $destino = Direcciones::select("id_direccion")
            ->where("direccion",$this->Utf8_ansi($body["meta"]["coordinate"][1]["address"]))
            ->where("empresa_id",$id_empresa)
            ->first();
            if(!$destino){
                $destino = Direcciones::create([
                    'empresa_id' => $id_empresa, 
                    'nombre'=> strtoupper($this->Utf8_ansi($body["meta"]["coordinate"][1]["name"])), 
                    'direccion' => strtoupper($this->Utf8_ansi($body["meta"]["coordinate"][1]["address"])), 
                    'duracion' => $body["meta"]["duration"], 
                    'distancia' => $body["meta"]["distance"], 
                    'precio' => $body["meta"]["price_fixed_value"],
                    'tipo' => "destino"
                ]);
            }
                        
            //Insertamos detalle viaje
            $det_viaje= DetViaje::create([
                "viaje_id" => $viaje->id_viaje,
                "origen_id" => $origen->id_direccion,
                "destino_id" => $destino->id_direccion,
                "vehiculo" => $body["meta"]["vehicle_name"],
                "no_maletas" => $body["vehicle_bag_count"],
                "no_pasajeros" => $body["vehicle_passenger_count"],
                "nombre" => strtoupper($this->Utf8_ansi($body["meta"]["client_contact_detail_first_name"]) ." ". $this->Utf8_ansi($body["meta"]["client_contact_detail_last_name"])),
                "correo" => $this->Utf8_ansi($body["meta"]["client_contact_detail_email_address"]),
                "telefono" => $this->Utf8_ansi($body["meta"]["client_contact_detail_phone_number"]),
                "tipo_pago" => $body["meta"]["payment_name"]
            ]);

            DB::commit();

            return ['ok' => true, "data" => "Registro Exitoso"];

        } catch(\Exception | \PDOException $e){
            Log::error("Error WebHookMyRide: ".$e->getMessage());
            DB::rollBack();
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }
}
