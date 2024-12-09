<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OperadorController as Operador;
use App\Models\ViajeModel as Viaje;

class ReporteController extends Controller
{
    public function index() {
        if(session('user')) {
            $user = json_decode($this->decode_json(session('user')[0]));
            $entries = ["public/js/reporte.js", "public/sass/reporte.scss"];
            $data_view = $this->getDataView($user);

            return view('admin/Reportes', compact('user','entries','data_view'));
        }
        return view('admin/template/Login');
    } 

    public function getDataView($user) {
        $operador = new Operador();

        return [
            "operadores" => $operador->obtenerOperadores(),
            "viajes" => $this->getViajes($user)
        ];
    }

    public function getViajes($user) {
        $viajes = Viaje::select("folio","status","date_creacion","dV.nombre","correo","telefono","tipo_pago","dO.nombre as origen","dD.nombre as destino","dD.precio")
        ->join("det_viaje as dV","dV.viaje_id","=","id_viaje")
        ->join("tbl_direcciones_webhook as dO","dO.id_direccion","=","dV.origen_id")
        ->join("tbl_direcciones_webhook as dD","dD.id_direccion","=","dV.destino_id")
        ->where("tbl_viajes.empresa_id",$user->id_empresa)
        ->orderBy("id_viaje","desc")
        ->limit(350)
        ->get();

        if(count($viajes) > 0) {
            return ["ok" => true, "data" => $viajes];
        }
        return ["ok" => false, "message" => "No se encontraron viajes"];
    }

    public function generarConsulta(Request $request) {
        try {
            if(session('user')) {
                $user = json_decode($this->decode_json(session('user')[0]));
                $columns=["id_viaje"];
                $columnas= $request->columnas;
                $fechaInicio = $request->dt_inicio;
                $fechaFin = $request->dt_fin;
                $tipo_caja = $request->filtro_caja;
                if(in_array("-1",$columnas)) {
                    $columnas = [0,1,2,3,4,7];
                } 
                foreach($columnas as $column) {
                    $string="";
                    switch($column) {
                        case 0: $columns[]="folio"; break;
                        case 1: $columns[]="dV.nombre"; $columns[]="dV.correo"; $columns[]="dV.telefono"; break; 
                        case 2: $columns[]="dO.nombre as origen"; $columns[]="dD.nombre as destino"; break; 
                        case 3: $columns[]="tbl_viajes.status"; break; 
                        case 4: $columns[]="dD.precio"; break; 
                        case 5: $columns[]="dD.distancia"; break; 
                        case 6: $columns[]="dD.duracion"; break; 
                        case 7: $columns[]="date_creacion as fecha_viaje"; break;  
                        case 8: $columns[]= "tblV.modelo"; $columns[]="tblV.vehiculo"; $columns[]="tblV.marca"; $columns[]="tblV.placa"; break; 
                        case 9: $columns[]="dV.tipo_pago"; break; 
                        case 10: $columns[]= $columns[]="tblO.nombres"; $columns[]="tblO.apellidos"; break; 
                        case 11: $columns[]="tbl_viajes.caja_id as no_caja"; break; 
                    }
                }
                if($request->tipo_filtro == 1) {
                    $viajes = Viaje::select($columns)
                    ->join("det_viaje as dV","dV.viaje_id","=","id_viaje")
                    ->join("tbl_direcciones_webhook as dO","dO.id_direccion","=","dV.origen_id")
                    ->join("tbl_direcciones_webhook as dD","dD.id_direccion","=","dV.destino_id")
                    ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
                    ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
                    ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
                    ->where("tbl_viajes.empresa_id",$user->id_empresa)
                    ->when(!empty($fechaInicio) && !empty($fechaFin), function ($query) use ($fechaInicio, $fechaFin) {
                        return $query->whereBetween('tbl_viajes.date_creacion', [$fechaInicio, $fechaFin]);
                    })
                    ->orderBy("id_viaje","desc")
                    ->limit(350)
                    ->get();
                    $tabla_viajes = view('components.tables.table_reportes', compact('viajes', 'columnas'))->render();
                }
                if($request->tipo_filtro == 2 && !empty($request->filtro_caja)) {
                    $consultaDiezDias = new \DateTime();
                    $consultaDiezDias->modify('-10 days');
                    $turnos = DB::table('tbl_turnos_caja')->select('id_det_caja','no_ventas','total_tarjeta','total_efectivo','total_venta','dt_inicio_operacion',"dt_fin_operacion")
                    ->where("caja_id",$tipo_caja)
                    ->where('dt_create',">=",$consultaDiezDias)->where('b_status',0)
                    ->when(!empty($fechaInicio) && !empty($fechaFin), function ($query) use ($fechaInicio, $fechaFin) {
                        return $query->whereBetween('dt_inicio_operacion', [date('Y-m-d H:i:s',strtotime($fechaInicio." 07:00:00")), date('Y-m-d H:i:s',strtotime($fechaFin." 24:00:00"))]);
                    })
                    ->get();
                    foreach($turnos as $turno) {
                        $turno->viajes = Viaje::select($columns)
                        ->join("det_viaje as dV","dV.viaje_id","=","id_viaje")
                        ->join("tbl_direcciones_webhook as dO","dO.id_direccion","=","dV.origen_id")
                        ->join("tbl_direcciones_webhook as dD","dD.id_direccion","=","dV.destino_id")
                        ->leftJoin("rel_viaje_vehiculo_operador as rlVVO","rlVVO.viaje_id","=","id_viaje")
                        ->leftJoin("rel_vehiculo_operador as rlVO","rlVO.id_vehiculo_operador","=","rlVVO.vehiculo_operador_id")
                        ->leftJoin("tbl_operadores as tblO","tblO.id_operador","=","rlVO.operador_id")
                        ->where("tbl_viajes.empresa_id",$user->id_empresa)
                        ->where("tbl_viajes.caja_id",$tipo_caja)
                        ->where('tbl_viajes.date_creacion',">=", date('Y-m-d H:i:s',strtotime($turno->dt_inicio_operacion)))
                        ->where('tbl_viajes.date_creacion','<=',date('Y-m-d H:i:s',strtotime($turno->dt_fin_operacion)))
                        ->orderBy("id_viaje","desc")
                        ->get();
                    }
                    $tabla_viajes = view('components.tables.table_reportes_cajas', compact('turnos', 'columnas','tipo_caja'))->render();
                }
                return ["ok"=> true, "data" => $tabla_viajes];
            }
            return ["ok" => false, "message" => "No cuentas con permisos"];
        } catch(\Exception | \PdoException | \Error $e) {
            return ["ok" => false, "message" => "Ha ocurrido un error: ".$e->getMessage()];
        }
        // $viajes = Viaje::select("folio","status","date_creacion","dV.nombre","correo","telefono","tipo_pago","dW.nombre as direccion","precio")
        // ->join("det_viaje as dV","dV.viaje_id","=","id_viaje")
        // ->join("tbl_direcciones_webhook as dW","dW.id_direccion","=","dV.destino_id")
        // ->where("tbl_viajes.empresa_id",$user->id_empresa)
        // ->orderBy("id_viaje","desc")
        // ->limit(350)
        // ->get();
    }
}
