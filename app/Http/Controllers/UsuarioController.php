<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UsuarioController extends Controller
{
    public function index() {
        if(!session('user')) {
            $entries = ['public/js/login.js','public/sass/login.scss'];
            return view('auth.login', compact('entries'));
        }
        return redirect()->action([AdminController::class, 'index']);
    }

    public function inicioOperacion(Request $request) {
        //Variables
        $user = null;
        #region [Validaciones]
            //Validamos la sesion
            if(session('user')) {
                $user = json_decode($this->decode_json(session('user')[0]));   
            }else {
                return ["ok" => false, "message" => "La sesion ha expirado"];
            }
            //Validamos si existe un turno sin cierre de esa caja
            $validar_turno =DB::table("tbl_turnos_caja")
            ->where("caja_id",$user->caja_id)
            ->where("b_status",1)
            ->first();
            if($validar_turno) {
                return ["ok" => false, "message" => "El turno anterior no realizo el cierre de operación"];
            }
        #endregion
        DB::table("tbl_turnos_caja")->insert([
            "caja_id" => $user->caja_id, 
            "b_status" => 1, 
            "dt_inicio_operacion" => date("Y-m-d H:i:s"), 
            "dt_create" => date('Y-m-d')
        ]);

        return ["ok" => true, "data" => "Has iniciado el turno a las ".date('d-m-Y H:i')];
    }

    public function cierreOperacion(Request $request) {
        //Variables
        $user = null;
        $total=0;
        #region [Validaciones]
            //Validamos la sesion
            if(session('user')) {
                $user = json_decode($this->decode_json(session('user')[0]));   
            }else {
                return ["ok" => false, "message" => "La sesion ha expirado"];
            }
            //Validamos si existe un turno sin cierre de esa caja
            $validar_turno =DB::table("tbl_turnos_caja")
            ->where("caja_id",$user->caja_id)
            ->where("b_status",1)
            ->first();
            if(!$validar_turno) {
                return ["ok" => false, "message" => "No hay iniciado un turno de operación para esta caja"];
            }
        #endregion
        //Obtener la informacion de venta
        $info_venta = DB::table("tbl_viajes as tblV")
        ->select("id_viaje","folio","tblDd.precio","tblV.status","dtV.tipo_pago","tTC.dt_inicio_operacion","tblV.date_creacion","tblDd.precio")
        ->join("det_viaje as dtV","dtV.viaje_id","=","id_viaje")
        ->join("tbl_turnos_caja as tTC","tTC.caja_id","=","tblV.caja_id")
        ->join("tbl_direcciones_webhook as tblDd","tblDd.id_direccion","dtV.destino_id")
        ->where("tblV.empresa_id",$user->id_empresa)
        ->where("tblV.status",'<>',"Cerrado")
        ->where("tblV.caja_id",$user->caja_id)
        ->where("tTC.b_status","1")
        ->get();

        $ventas= [];
        $total_ventas=0;
        $total_efectivo= 0;
        $total_tarjeta=0;
        $ids_cerrar=[];
        foreach($info_venta as $venta) {
            if($venta->date_creacion >= $venta->dt_inicio_operacion) {
                $total_ventas++;
                if($venta->tipo_pago == "Cash") {
                    $total_efectivo += $venta->precio; 
                } else {
                    $total_tarjeta += $venta->precio;
                }
                array_push($ids_cerrar, $venta->id_viaje);
                array_push($ventas, $venta);
            }
        }
        $total = floatval($total_efectivo) + floatval($total_tarjeta);
        DB::table("tbl_turnos_caja")
        ->where("id_det_caja",$validar_turno->id_det_caja)
        ->update([
            "caja_id" => $user->caja_id, 
            "b_status" => 0, 
            "dt_fin_operacion" => date("Y-m-d h:i:s"), 
            "total_venta" => $total
        ]);
        DB::table("tbl_viajes")
        ->whereIn("id_viaje",$ids_cerrar)
        ->update([
            "status" => 'Cerrado'
        ]);
        
        return [
            "ok" => true, 
            "data" => "El turno ha sido cerrado correctamente, a contuniacion se mostrará el detalle de tu cierre",
            "caja" => $user->nombre,
            "ventas" => $ventas,
            "totales" => [
                "no_ventas" => $total_ventas,
                "total_cash" => $total_efectivo,
                "total_tarjet" => $total_tarjeta,
                "total" => $total
            ]
        ];
    }

    public function login(Request $body) {
        try {
            $user = DB::table("tbl_usuarios as tblU")
            ->select("id_usuario","usuario","nombre","password","tblE.id_empresa","tblE.empresa","tblE.logo_path","tblE.webhook","perfil_id", "caja_id")
            ->leftJoin("tbl_empresas as tblE","tblE.id_empresa","=","tblU.empresa_id")
            ->leftJoin("rel_usuario_caja as rUC","rUC.usuario_id","=","id_usuario")
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
                    $permisos = DB::table("tbl_perfiles")
                    ->where('id_perfil',$user->perfil_id)
                    ->first();
                    $user_data = [
                        "id_usuario" => $user->id_usuario,
                        "usuario" => $user->usuario,
                        "nombre" => $user->nombre,
                        "id_empresa" => $user->id_empresa,
                        "webhook" => $user->webhook,
                        "empresa" => $user->empresa,
                        "logo_path" => $user->logo_path,
                        "menu" => $menu,
                        "permisos" => $permisos,
                        "caja_id" => $user->caja_id
                    ];
                    session(["user" => [$this->encode_json(json_encode($user_data))]]);
                    return ["ok" => true, "data" => $user_data];
                }
                return ["ok" => false, "message" => "E-AD-001 : Contraseña invalida"];
            }
            return ["ok" => false, "message" => "E-AD-001 : Usuario no encontrado"];
        } catch(\Exception | \PDOException $e) {
            return ["ok" => false, "message" => "E-AD-GEN : ".$e->getMessage()];
        }
    }

    public function logout() {
        session()->forget("user");
        return redirect()->route('index');
    }
}
