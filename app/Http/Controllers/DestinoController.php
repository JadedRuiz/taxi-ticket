<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinoModel as Destino;
use Illuminate\Support\Facades\DB;

class DestinoController extends Controller
{

    function index() {
        if(session()->has('data-user')) {
            $user = json_decode($this->decode_json(session('data-user')));
            $destinos = $this->obtenerDestinos();
            $entries = ["public/js/destino.js"];

            return view('admin/Destinos', compact('user','entries','destinos'));
        }
        return view('admin/template/Login');
    }

    function obtenerDestinos() {
        try {
            
            $destinos = Destino::select('id_destino as iIdDestino','destino as sNombre',"duracion","precio","distancia","activo","tblD.calle","tblD.num_ext","tblD.colonia","tblD.cp","tblD.ciudad","tblD.num_int","direccion_id")
            ->leftJoin("tbl_direcciones as tblD","tblD.id_direccion","=","direccion_id")
            ->get();
            foreach($destinos as $destino) {
                if($destino->direccion_id != null) {
                    $noint = "";
                    if($destino->num_int != null) {
                        $noint = "Int ".$destino->num_int.", ";
                    }                
                    $destino->sDireccion = "Calle ".$destino->calle." ".$destino->num_ext.", ".$noint.$destino->colonia.", ".$destino->cp.", ".$destino->ciudad;
                }
            }
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

    function obtenerDestinoIdAdmin(Request $body) {
        try {

            $destino= Destino::select("destino",'precio', 'duracion', 'distancia', 'tblD.calle', 'tblD.num_ext', 'tblD.num_int', 'tblD.colonia', 'tblD.cp', 'tblD.ciudad')
            ->leftJoin("tbl_direcciones as tblD","tblD.id_direccion","=","tbl_destinos.direccion_id")
            ->where("id_destino",$body["id_destino"])
            ->first();
            return [ 'ok' => true, "data" => $destino ];

        } catch(\Exception | \PDOException $e){
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
    }

    function guardarDestino(Request $body) {
        try {
            DB::beginTransaction();
            if($body["id_destino"] == "0") {
                $id_direccion = null;
                if($body["calle"] != null) {
                    $id_direccion = DB::table('tbl_direcciones')->insertGetId(
                        [
                            'calle' => $body["calle"],
                            'colonia' => $body["colonia"],
                            'ciudad' => $body["ciudad"],
                            'num_int' => $body["no_int"],
                            'num_ext' => $body["no_ext"],
                            'cp' => $body["cp"]
                        ]
                    );
                }
                Destino::create([
                    'direccion_id' => $id_direccion,
                    'destino' => $body["destino"],
                    'precio' => $body["precio"],
                    'distancia' => $body['distancia'],
                    'duracion' => $body["duracion"],
                    'date_creacion' => date('Y-m-d h:m:s'),
                    'activo' => 1
                ]);
            }else {
                $id_direccion = Destino::select('direccion_id')->where("id_destino",$body["id_destino"])->first()->direccion_id;
                if($id_direccion != null) {
                    DB::table('tbl_direcciones')
                    ->where("id_direccion",$id_direccion)
                    ->update(
                        [
                            'calle' => $body["calle"],
                            'colonia' => $body["colonia"],
                            'ciudad' => $body["ciudad"],
                            'num_int' => $body["no_int"],
                            'num_ext' => $body["no_ext"],
                            'cp' => $body["cp"]
                        ]
                    );
                }else{
                    if($body["calle"] != null) {
                        $id_direccion = DB::table('tbl_direcciones')->insertGetId(
                            [
                                'calle' => $body["calle"],
                                'colonia' => $body["colonia"],
                                'ciudad' => $body["ciudad"],
                                'num_int' => $body["no_int"],
                                'num_ext' => $body["no_ext"],
                                'cp' => $body["cp"]
                            ]
                        );
                    }
                }
                
                Destino::where('id_destino',$body["id_destino"])->update([
                    'direccion_id' => $id_direccion,
                    'destino' => $body["destino"],
                    'precio' => $body["precio"],
                    'distancia' => $body['distancia'],
                    'duracion' => $body["duracion"],
                ]);
            }
            DB::commit();
            return [ 'ok' => true, "data" => "Se ha guardado correctamente" ];
        } catch(\Exception | \PDOException $e){
            DB::rollBack();
            return [ 'ok' => false, "data" => "Ha ocurrido un error: ". $e->getMessage() ];
        }
    }
}
