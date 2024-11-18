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
use App\Http\Controllers\VehiculosController as Vehiculos;
use App\Models\OperadorModel as Operador;
use Illuminate\Support\Facades\Mail;
use App\Events\ActualizarTurno;
use App\Events\ActualizarViajes;
use GuzzleHttp\Client;

class AdminController extends Controller
{
    //
    function index() {
        if(session('user')) {
            $user = json_decode($this->decode_json(session('user')[0]));            
            $entries = ['public/js/admin.js','public/sass/admin.scss'];
            $turno_caja = $this->obtenerTurnoCaja($user->caja_id);
            $reservaciones = [];
            if(in_array($user->permisos->perfil, ["Cajera"]) && $turno_caja["ok"]) {
                $reservaciones_totales = DB::table("tbl_viajes as tblV")
                ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio","tblV.status", "tblO.nombres","tblO.apellidos","dtV.tipo_pago","tTC.dt_inicio_operacion")
                ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
                ->join("tbl_turnos_caja as tTC","tTC.caja_id","=","tblV.caja_id")
                ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
                ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
                ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
                ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
                ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
                ->where("tblV.empresa_id",$user->id_empresa)
                ->where("tblV.status",'<>',"Cerrado")
                ->where("tblV.caja_id",$user->caja_id)
                ->where("tTC.b_status","1")
                ->orderBy("tblV.date_creacion",'DESC')
                // ->orderBy("tblV.folio",'DESC')
                ->get();
                $reservaciones = [];
                foreach($reservaciones_totales as $reservacion) {
                    if($reservacion->date_creacion >=  $reservacion->dt_inicio_operacion) {
                        array_push($reservaciones,$reservacion);
                    }
                }
            }
            if(in_array($user->permisos->perfil, ["Administrador"])) {
                $reservaciones_totales = DB::table("tbl_viajes as tblV")
                ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio","tblV.status", "tblO.nombres","tblO.apellidos","dtV.tipo_pago","tTC.dt_inicio_operacion")
                ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
                ->join("tbl_turnos_caja as tTC","tTC.caja_id","=","tblV.caja_id")
                ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
                ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
                ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
                ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
                ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
                ->where("tblV.empresa_id",$user->id_empresa)
                ->where("tblV.status",'<>',"Cerrado")
                ->where("tTC.b_status","1")
                ->orderBy("tblV.date_creacion",'DESC')
                // ->orderBy("tblV.folio",'DESC')
                ->get();
                $reservaciones = [];
                foreach($reservaciones_totales as $reservacion) {
                    if($reservacion->date_creacion >=  $reservacion->dt_inicio_operacion) {
                        array_push($reservaciones,$reservacion);
                    }
                }
            }
            if(in_array($user->permisos->perfil, ["Operador"])) {
                $reservaciones = DB::table("tbl_viajes as tblV")
                ->select("id_viaje","folio","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblV.status", "tblO.nombres","tblO.apellidos")
                ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
                ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
                ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
                ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
                ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
                ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
                ->where("tblV.empresa_id",$user->id_empresa)
                ->where("tblV.status",'<>',"Cerrado")
                ->orderBy("tblV.date_creacion",'DESC')
                // ->orderBy("tblV.folio",'DESC')
                ->get();
            }
            
            $vehiculos = $this->obtenerVehiculosOperadores();
            $turnos = $this->obtenerTurnos($user);
            return view('admin.Home', compact('user','reservaciones','entries','vehiculos','turnos', 'turno_caja'));
        }
        return redirect()->action([UsuarioController::class, 'index']);
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
            $datos_vehiculo_operador = DB::table("rel_viaje_vehiculo_operador as rlVVO")
            ->select("vehiculo","nombres","apellidos","marca","modelo","placa")
            ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","vehiculo_operador_id")
            ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
            ->leftJoin("tbl_vehiculos as tblV","tblV.id_vehiculo","=","rlVO.vehiculo_id")
            ->where("viaje_id",$viaje->id_viaje)
            ->first();
            //Generamos el PDF en B64
            try {
                $pdf_b64= Ticket::generarTicket($viaje, $det_viaje, $destino, $origen, $validar_empresa, $datos_vehiculo_operador);
                return ["ok"=> true, "data" => $pdf_b64];
            }catch(\Exception $e) {
                return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
            }
        }else{
            $origen = DB::table("tbl_origenes")
            ->where("id_origen",$det_viaje->origen_id)
            ->first();
            $destino = DB::table("tbl_destinos")
            ->where("id_destino",$det_viaje->destino_id)
            ->first();
            //Generamos el PDF en B64
            try {
                $pdf_b64= Ticket::generarTicket($viaje, $det_viaje, $destino, $origen, $validar_empresa);
                return ["ok"=> true, "data" => $pdf_b64];
            }catch(\Exception $e) {
                return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
            }
        }
    }

    public function encriptar(Request $body) {
        return $this->decode_json($body["password"]);
    }

    public function obtenerTurnoCaja($caja_id) {
        if($caja_id != null) {
            $turno_caja = DB::table('tbl_turnos_caja')
            ->select('dt_inicio_operacion')
            ->where('b_status',1)
            ->where('caja_id',$caja_id)
            ->first();
            if($turno_caja) {
                return ["ok" => true, "data" => $turno_caja];
            }
            return ["ok" => false, "message" => "Aun no se ha iniciado operaciones"];
        }
        return null;
    }
    
    //WEBHOOK's
    public function webHookMyRide($id_empresa, Request $body) {
        try{
            //Validación
            $validar = Viaje::where("folio",$body["post"]["ID"])->first();
            if($validar) {
                return ["ok" => false, "data" => "Este folio ya ha sido registrado"];
            }
            DB::beginTransaction();
            $caja_id=0;
            if(isset($body["meta"]["form_element_field"]) && is_array($body["meta"]["form_element_field"]) && count($body["meta"]["form_element_field"]) > 0) {
                if(isset($body["meta"]["form_element_field"][0]["label"]) && isset($body["meta"]["form_element_field"][0]["value"])) {
                    $caja_id = $body["meta"]["form_element_field"][0]["value"];
                }
            }
            //Insertamos el viaje
            $viaje = Viaje::create([
                "empresa_id" => $id_empresa,
                "caja_id" => $caja_id,
                "folio" => $body["post"]["ID"],
                "nombre_viaje" => $body["post"]["post_title"],
                "status" => $body["booking_status_name"], //Pendiente
                "tipo_servicio" => $body["service_type_name"],
                "tipo_viaje" => $body["transfer_type_name"],
                "date_creacion" => $body["meta"]["pickup_datetime"],
                "comentarios" => $body["comment"]
            ]);
            //Insertamos las direcciones
            $origen = Direcciones::select("id_direccion",'nombre as origen','precio','distancia','duracion')
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
            $destino = Direcciones::select("id_direccion",'nombre as destino','precio','distancia','duracion')
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
            }else{
                $destino->update([
                    "precio" => $body["meta"]["price_fixed_value"]
                ]);
            }
            //Validamos si existe facturacio
            $id_factura=0;
            if($body["meta"]["client_billing_detail_enable"] == 1) {
                $data_facturacion = [
                    "razon_social" => $body["meta"]["client_billing_detail_company_name"],
                    "rfc" => $body["meta"]["client_billing_detail_tax_number"],
                    "calle" => $body["meta"]["client_billing_detail_street_name"],
                    "no_calle" => $body["meta"]["client_billing_detail_street_number"],
                    "ciudad" => $body["meta"]["client_billing_detail_city"],
                    "estado" => $body["meta"]["client_billing_detail_state"],
                    "codigo_postal" => $body["meta"]["client_billing_detail_postal_code"],
                    "pais" => $body["meta"]["client_billing_detail_country_code"]
                ];
                $id_factura = DB::table("tbl_facturas")
                ->insertGetId($data_facturacion);
            }
            //Buscamos quien realizo la venta
            //Insertamos detalle viaje
            $det_viaje= DetViaje::create([
                "viaje_id" => $viaje->id_viaje,
                "origen_id" => $origen->id_direccion,
                "destino_id" => $destino->id_direccion,
                "factura_id" => $id_factura,
                "vehiculo" => $body["meta"]["vehicle_name"],
                "no_maletas" => $body["vehicle_bag_count"],
                "no_pasajeros" => $body["vehicle_passenger_count"],
                "nombre" => strtoupper($this->Utf8_ansi($body["meta"]["client_contact_detail_first_name"]) ." ". $this->Utf8_ansi($body["meta"]["client_contact_detail_last_name"])),
                "correo" => $this->Utf8_ansi($body["meta"]["client_contact_detail_email_address"]),
                "telefono" => $this->Utf8_ansi($body["meta"]["client_contact_detail_phone_number"]),
                "tipo_pago" => $body["meta"]["payment_name"]
            ]);

            //Enviamos correo para avisar al admin que se realizo un viaje con factura
            if($id_factura != 0 && isset($data_facturacion)) {
                Mail::send('plantillas.ticket_correo', compact('viaje','det_viaje','destino','origen','data_facturacion'), function ($message) use ($body){
                    $message->subject('Facturacion MyRide Folio#'.$body["post"]["ID"]);
                    $message->to(getenv('MAIL_ADMIN'),'Administrador MyRide');
                });
            }

            //Actualizamos las cajas
            broadcast(new ActualizarViajes([
                "caja_id" => $caja_id
            ]));
            //Actualizamos admin
            broadcast(new ActualizarViajes([
                "caja_id" => -1
            ]));

            
            DB::commit();            

            return ['ok' => true, "data" => "Registro Exitoso"];

        } catch(\Exception | \PDOException $e){
            Log::error("Error WebHookMyRide: ".$e->getMessage());
            DB::rollBack();
            return ['ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    public function obtenerVehiculosOperadores() {
        try {
            $cls_vehiculos = new Vehiculos();
            $vehiculos = $cls_vehiculos->obtenerVehiculos();
            if($vehiculos["ok"] && count($vehiculos["data"]) > 0) {
                foreach($vehiculos["data"] as $vehiculo) {
                    $operadores = Operador::select("nombres","apellidos","id_vehiculo_operador")
                    ->join("rel_vehiculo_operador as tblVO","tblVO.operador_id","=","id_operador")
                    ->where("tblVO.vehiculo_id",$vehiculo->id_vehiculo)
                    ->where("tblVO.activo",1)
                    ->get();

                    $vehiculo->operadores = $operadores;
                }
                return [ "ok" => true, "data" => $vehiculos["data"]];
            }
            return ["ok" => true, "data" => []];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerVehiculosOperadores]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado operadores"];
        }
    }

    public function agregarNuevoTurno(Request $request) {
        try {
            $user = json_decode($this->decode_json(session('user')[0]));
            $validar = DB::table("tbl_turnos")
            ->where("empresa_id",$user->id_empresa)
            ->where("vehiculo_operador_id",$request->id_vehiculo_operador)
            ->where("dtCreacion",">=",date('Y-m-d'))
            ->where("activo",1)
            ->first();
            if($validar) {
                return [ "ok" => false, "message" => "Ya existe un turno en la lista con el vehiculo y operador seleccionado"];
            }
            if($request->id_vehiculo_operador == null) {
                return [ "ok" => false, "message" => "El id_vehiculo_operador es obligatorio"];
            }
            DB::table("tbl_turnos")->insert([
                "empresa_id" => $user->id_empresa,
                "vehiculo_operador_id" => $request->id_vehiculo_operador,
                "dtCreacion" => date("Y-m-d h:i:s"),
                "activo" => 1
            ]);

            //Lanzamos el evento para actualizar todos los clientes
            $turnosActualizados = $this->obtenerTurnos($user);
            broadcast(new ActualizarTurno($turnosActualizados));

            return [ "ok" => true, "data" => "Turno agregado con exito"];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [agregarNuevoTurno]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado operadores"];
        }
    }

    public function eliminarTurno(Request $request) {
        try {
            $user = json_decode($this->decode_json(session('user')[0]));
            $validar = DB::table("tbl_turnos")
            ->where("empresa_id",$user->id_empresa)
            ->where("id_turno",$request->id_turno)
            ->where("dtCreacion",">=",date('Y-m-d'))
            ->where("activo",1)
            ->first();
            if(!$validar) {
                return [ "ok" => false, "message" => "Este turno no existe en la lista de turnos"];
            }
            DB::table("tbl_turnos")
            ->where("id_turno", $request->id_turno)
            ->update([
                "activo" => 0
            ]);
            //Lanzamos el evento para actualizar todos los clientes
            $turnosActualizados = $this->obtenerTurnos($user);
            broadcast(new ActualizarTurno($turnosActualizados));

            return [ "ok" => true, "data" => "Turno eliminado con exito"];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [eliminarTurno]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado operadores"];
        }
    }

    public function obtenerTurnos($user) {
        try {
            $turnos = DB::table("tbl_turnos as tblT")
            ->select("tblT.id_turno","rlVO.id_vehiculo_operador","tblV.vehiculo","tblV.marca","tblV.modelo","tblO.nombres","tblO.apellidos")
            ->join("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","tblT.vehiculo_operador_id")
            ->join("tbl_vehiculos as tblV","tblV.id_vehiculo","=","rlVO.vehiculo_id")
            ->join("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
            ->where("empresa_id",$user->id_empresa)
            ->where("tblT.dtCreacion",">=",date('Y-m-d'))
            ->where("tblT.activo",1)
            ->orderBy('tblT.id_turno','desc')
            ->get();
            return ["ok" => true, "data" => $turnos];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerTurnos]: ".$e->getMessage());
            return ["ok" => false, "message" => "No se han encontrado turnos"];
        }
    }

    public function obtenerTurnosAsync() {
        $user = json_decode($this->decode_json(session('user')[0]));
        return $this->obtenerTurnos($user);
    }

    public function asignarOperadorAViaje(Request $request) {
        try {
            $user = json_decode($this->decode_json(session('user')[0]));
            $siguiente_turno = DB::table("tbl_turnos")
            ->where("empresa_id",$user->id_empresa)
            ->where("dtCreacion",">=",date('Y-m-d'))
            ->where("activo",1)
            ->orderBy("dtCreacion", "asc")
            ->first();
            if($siguiente_turno) {
                //Validamos si el viaje ya cuenta con operador
                $validar_viaje = DB::table("rel_viaje_vehiculo_operador")
                ->where('viaje_id',$request->id_viaje)
                ->first();
                if($validar_viaje){
                    DB::table("rel_viaje_vehiculo_operador")
                    ->where('viaje_id',$request->id_viaje)
                    ->update([
                        "vehiculo_operador_id" => $siguiente_turno->vehiculo_operador_id
                    ]);
                } else {
                    DB::table("rel_viaje_vehiculo_operador")->insert([
                        "viaje_id" => $request->id_viaje,
                        "vehiculo_operador_id" => $siguiente_turno->vehiculo_operador_id,
                        "dtCreacion" => date("Y-m-d h:i:s"),
                        "activo" => 1
                    ]);
                }
                
                //Actualizamos los turnos
                DB::table("tbl_turnos")
                ->where("id_turno",$siguiente_turno->id_turno)
                ->update([
                    "activo" => 0
                ]);
                //Actualizamos el viaje
                Viaje::where("id_viaje",$request->id_viaje)->update([
                    "status" => "En servicio"
                ]);
                //Lanzamos el evento para actualizar todos los clientes
                $turnosActualizados = $this->obtenerTurnos($user);
                broadcast(new ActualizarTurno($turnosActualizados));

                //Actualizamos todos los clientes
                broadcast(new ActualizarViajes([
                    "caja_id" => 0
                ]));

                return [ "ok" => true, "data" => "El Operador ha sido asignado al viaje"];
            }
            return [ "ok" => false, "message" => "Aun no existen turnos en la lista"];

        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [asignarOperadorAViaje]: ".$e->getMessage());
            return ["ok" => false, "message" => "Ha ocurrido un error al asignar el viaje"];
        }
    }

    public function asignarOperadorAViajeAdmin(Request $request) {
        try {
            $user = json_decode($this->decode_json(session('user')[0]));
            $siguiente_turno = DB::table("tbl_turnos")
            ->where("empresa_id",$user->id_empresa)
            ->where("dtCreacion",">=",date('Y-m-d'))
            ->where("activo",1)
            ->orderBy("dtCreacion", "asc")
            ->first();
            if($siguiente_turno) {
                //Validamos si el viaje ya cuenta con operador
                $validar_viaje = DB::table("rel_viaje_vehiculo_operador")
                ->where('viaje_id',$request->id_viaje)
                ->first();
                if($validar_viaje){
                    DB::table("rel_viaje_vehiculo_operador")
                    ->where('viaje_id',$request->id_viaje)
                    ->update([
                        "vehiculo_operador_id" => $request->id_vehiculo_operador
                    ]);
                } else {
                    DB::table("rel_viaje_vehiculo_operador")->insert([
                        "viaje_id" => $request->id_viaje,
                        "vehiculo_operador_id" => $request->id_vehiculo_operador,
                        "dtCreacion" => date("Y-m-d h:i:s"),
                        "activo" => 1
                    ]);
                }
                $obtnemos_el_turno = DB::table("tbl_turnos")
                ->where("empresa_id",$user->id_empresa)
                ->where("dtCreacion",">=",date('Y-m-d'))
                ->where("activo",1)
                ->where("vehiculo_operador_id",$request->id_vehiculo_operador)
                ->first();

                //Actualizamos los turnos
                DB::table("tbl_turnos")
                ->where("id_turno",$obtnemos_el_turno->id_turno)
                ->update([
                    "activo" => 0
                ]);
                //Actualizamos el viaje
                Viaje::where("id_viaje",$request->id_viaje)->update([
                    "status" => "En servicio"
                ]);
                //Lanzamos el evento para actualizar todos los clientes
                $turnosActualizados = $this->obtenerTurnos($user);
                broadcast(new ActualizarTurno($turnosActualizados));

                //Actualizamos todos los clientes
                broadcast(new ActualizarViajes([
                    "caja_id" => 0
                ]));

                return [ "ok" => true, "data" => "El Operador ha sido asignado al viaje"];
            }
            return [ "ok" => false, "message" => "Aun no existen turnos en la lista"];

        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [asignarOperadorAViaje]: ".$e->getMessage());
            return ["ok" => false, "message" => "Ha ocurrido un error al asignar el viaje"];
        }
    }

    public function cancelarViaje(Request $res) {
        try {
            DB::table('tbl_viajes')->where('id_viaje', $res->id_viaje)
            ->update([
                "status" => "Cancelado"
            ]);
            
            //Actualizamos todos los clientes
            broadcast(new ActualizarViajes([
                "caja_id" => 0
            ]));
            
            return ["ok" => true, "message" => "El viaje ha sido cancelado."];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [asignarOperadorAViaje]: ".$e->getMessage());
            return ["ok" => false, "message" => "Ha ocurrido un error al asignar el viaje"];
        }
    }

    public function obtenerReservasCaja(Request $request) {
        try {
            $caja_id = $request->caja_id;
            $reservaciones_totales = DB::table("tbl_viajes as tblV")
            ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio","tblV.status", "tblO.nombres","tblO.apellidos","dtV.tipo_pago","tTC.dt_inicio_operacion")
            ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
            ->join("tbl_turnos_caja as tTC","tTC.caja_id","=","tblV.caja_id")
            ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
            ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
            ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
            ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
            ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
            ->where("tblV.empresa_id",$request->id_empresa)
            ->where("tblV.status",'<>',"Cerrado")
            ->where("tblV.caja_id",$request->caja_id)
            ->where("tTC.b_status","1")
            ->orderBy("tblV.date_creacion",'DESC')
            // ->orderBy("tblV.folio",'DESC')
            ->get();

            //Admin pidio actualizar su tabla
            if($request->caja_id == null || $request->caja_id == 0 ||!isset($request->caja_id)) {
                $reservaciones_totales = DB::table("tbl_viajes as tblV")
                ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblV.date_creacion","tblDo.nombre as origen","tblDd.nombre as destino","tblDd.precio","tblV.status", "tblO.nombres","tblO.apellidos","dtV.tipo_pago","tTC.dt_inicio_operacion")
                ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
                ->join("tbl_turnos_caja as tTC","tTC.caja_id","=","tblV.caja_id")
                ->leftJoin("tbl_direcciones_webhook as tblDo","tblDo.id_direccion","dtV.origen_id")
                ->leftJoin("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
                ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
                ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
                ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
                ->where("tblV.empresa_id",$request->id_empresa)
                ->where("tblV.status",'<>',"Cerrado")
                ->orderBy("tblV.date_creacion",'DESC')
                ->where("tTC.b_status","1")
                // ->orderBy("tblV.status","DESC")
                ->get();
            }
            
            $reservaciones = [];
            foreach($reservaciones_totales as $reservacion) {
                if($reservacion->date_creacion >=  $reservacion->dt_inicio_operacion) {
                    array_push($reservaciones,$reservacion);
                }
            }
            return ["ok" => true, "data" => view('components.tables.table_viajes', compact('reservaciones', 'caja_id'))->render()];
        } catch(\PdoException | \Error | \Exception $e) {
            Log::error("ERROR En método [obtenerReservasCaja]: ".$e->getMessage());
            return ["ok" => false, "message" => "Ha ocurrido al recuperar los viajes"];
        }
    }
}