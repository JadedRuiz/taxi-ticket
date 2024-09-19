<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Exports\TicketExport as Ticket;

class AdminController extends Controller
{
    //
    function index() {
        if(session()->has('data-user')) {
            $user = json_decode($this->decode_json(session('data-user')));
            $reservaciones = DB::table("tbl_viajes as tblV")
            ->select("id_viaje","folio","dtV.nombre","dtV.correo","dtV.telefono","tblD.destino","tblD.precio","tblO.origen","tblV.date_creacion")
            ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
            ->leftJoin("tbl_destinos as tblD","tblD.id_destino","=","dtV.destino")
            ->leftJoin("tbl_origenes as tblO","tblO.id_origen","=","dtV.origen")
            ->where("empresa_id",$user->id_empresa)
            ->orderBy("tblV.date_creacion",'DESC')
            ->get();
            return view('admin/Home', compact('user','reservaciones'));
        }
        return view('admin/template/Login');
    }

    public function login(Request $body) {
        try {
            $user = DB::table("tbl_usuarios as tblU")
            ->select("usuario","nombre","password","tblE.id_empresa","tblE.empresa","tblE.logo_path")
            ->leftJoin("tbl_empresas as tblE","tblE.id_empresa","=","tblU.empresa_id")
            ->where("usuario",$body["usuario"])
            ->where("tblU.activo",1)
            ->first();

            if($user) {
                if($this->decode_json($user->password) == $body["pass"]) {
                    Session::put("data-user",$this->encode_json(json_encode($user)));
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
        $destino = DB::table("tbl_destinos")
        ->where("id_destino",$det_viaje->destino)
        ->first();
        $origen = DB::table("tbl_origenes")
        ->where("id_origen",$det_viaje->origen)
        ->first();
        //Generamos el PDF en B64
        try {
            $pdf_b64= Ticket::generarTicket($viaje, $det_viaje, $destino, $origen);
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
}
