<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GastoModel as Gasto;
use App\Exports\GastoExport as GastoPDF;
use Log;

class GastoController extends Controller
{
    public function index() {
        if(session('user')) {
            $user = json_decode($this->decode_json(session('user')[0]));
            $entries = ["public/js/gasto.js", "public/sass/gasto.scss"];
            $data_view = $this->getDataView();

            return view('admin/Gastos', compact('user','entries','data_view'));
        }
        return view('admin/template/Login');
    }

    public function getDataView() {
        $data=[
            "folio" => "00001",
            "encargados" => [],
            "gastos" => [],
            "tipo_comprobante" => ["RECIBO", "FACTURA"]
        ];
        $lastFolio = Gasto::select("folio")->orderBy("id_gasto","desc")->first();
        $data["gastos"] = Gasto::select("id_gasto","concepto_gasto","folio","importe","status","fecha")->orderBy("folio","desc")->get();
        $data["encargados"] = DB::table("tbl_admin_gastos")->select("id_admin_gastos","nombre")->get();
        if($lastFolio) {
            $number = intval($lastFolio->folio);
            $number++;
            $folio="";
            for($i=strlen("".$number); $i<5; $i++) {
                $folio.= "0";
            }
            $folio.=$number."";
            $data["folio"] = $folio;
        }

        return $data;
    }

    public function getLastFolio() {
        $lastFolio = Gasto::select("folio")->orderBy("id_gasto","desc")->first();
        if($lastFolio) {
            $number = intval($lastFolio->folio);
            $number++;
            $folio="";
            for($i=strlen("".$number); $i<5; $i++) {
                $folio.= "0";
            }
            $folio.=$number."";
            return ["ok" => true, "folio" => $folio];
        }
        return ["ok" => true, "folio" => "00001"];
    }

    public function getConcepts() {
        $conceptos_db = DB::table("tbl_concepto_gastos")->select("concepto")->get();
        $conceptos=[];
        if(count($conceptos_db) > 0) {
            foreach($conceptos_db as $concepto) {
                array_push($conceptos,$concepto->concepto);
            }
            return ["ok" => true, "data" => $conceptos];
        }
        return ["ok" => false, "message" => "No se encontraron resultados"];
    }

    public function create(Request $request) {
        try {
            #region [Validaciones]
                if(!isset($request->folio) || empty($request->folio)) return ['ok' => false, "message" => "El campo Folio es obligatorio"];
                if(!isset($request->concepto_gasto) || empty($request->concepto_gasto)) return ['ok' => false, "message" => "El campo Clasificación de gasto es obligatorio"];
                if(!isset($request->fecha) || empty($request->fecha)) return ['ok' => false, "message" => "El campo Fecha es obligatorio"];
                if(!isset($request->importe) || empty($request->importe)) return ['ok' => false, "message" => "El campo Importe es obligatorio"];
                if(!isset($request->recibe_efectivo) || empty($request->recibe_efectivo)) return ['ok' => false, "message" => "El campo Recibe efectivo es obligatorio"];
                if(session('user')) {
                    $user = json_decode($this->decode_json(session('user')[0]));
                } else {
                    return ['ok' => false, "message" => "Su sesión ha caducado, ingrese de nuevo"];
                }
            #endregion
            DB::beginTransaction();
            $gasto = Gasto::create([
                "concepto_gasto" => strtoupper($request->concepto_gasto),
                "folio" => $request->folio,
                "fecha" => $request->fecha,
                "status" => $request->status,
                "importe" => $request->importe,
                "descripcion" => strtoupper($request->descripcion),
                "tipo_comprobante" => $request->tipo_comprobante,
                "folio_comprobante" => $request->folio_comprobante,
                "recibe_efectivo" => strtoupper($request->recibe_efectivo),
                "admin_gastos_id" => $request->admin_gastos_id,
                "fecha_creacion" => date('Y-m-d H:i:s'),
                "usuario_creacion" => $user->id_usuario

            ]);
            DB::commit();
            return ['ok' => true, "data" => "El gasto se ha agreado de manera exitosa", "id" => $gasto->id_gasto ];
        } catch(\Exception | \PDOException $e){
            DB::rollBack();
            Log::error("[Gasto::create] - Ha ocurrido un error:". $e->getMessage());
            return ['ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    public function update(Request $request) {
        try {
            #region [Validaciones]
                if(!isset($request->folio) || empty($request->folio)) return ['ok' => false, "message" => "El campo Folio es obligatorio"];
                if(!isset($request->concepto_gasto) || empty($request->concepto_gasto)) return ['ok' => false, "message" => "El campo Clasificación de gasto es obligatorio"];
                if(!isset($request->fecha) || empty($request->fecha)) return ['ok' => false, "message" => "El campo Fecha es obligatorio"];
                if(!isset($request->importe) || empty($request->importe)) return ['ok' => false, "message" => "El campo Importe es obligatorio"];
                if(!isset($request->recibe_efectivo) || empty($request->recibe_efectivo)) return ['ok' => false, "message" => "El campo Recibe efectivo es obligatorio"];
                if(session('user')) {
                    $user = json_decode($this->decode_json(session('user')[0]));
                } else {
                    return ['ok' => false, "message" => "Su sesión ha caducado, ingrese de nuevo"];
                }
            #endregion
            DB::beginTransaction();
            Gasto::where("id_gasto",$request->id_gasto)->update([
                "concepto_gasto" => strtoupper($request->concepto_gasto),
                "folio" => $request->folio,
                "fecha" => $request->fecha,
                "status" => $request->status,
                "importe" => $request->importe,
                "descripcion" => strtoupper($request->descripcion),
                "tipo_comprobante" => $request->tipo_comprobante,
                "folio_comprobante" => $request->folio_comprobante,
                "recibe_efectivo" => strtoupper($request->recibe_efectivo),
                "admin_gastos_id" => $request->admin_gastos_id,
                "fecha_creacion" => date('Y-m-d H:i:s'),
                "usuario_creacion" => $user->id_usuario

            ]);
            DB::commit();
            return ['ok' => true, "data" => "El gasto se ha actualizado de manera exitosa"];
        } catch(\Exception | \PDOException $e){
            DB::rollBack();
            Log::error("[Gasto::update] - Ha ocurrido un error:". $e->getMessage());
            return ['ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    public function getGastoById(Request $request) {
        try {
            return ["ok" => true, "data" => Gasto::where("id_gasto",$request->id_gasto)->first()];
        }catch(\Exception | \PDOException $e){
            return ['ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage()];
        }
    }

    public function generateFichaGasto(Request $request) {
        try {
            if(session('user')) {
            $user = json_decode($this->decode_json(session('user')[0]));
            } else {
                return ['ok' => false, "message" => "Su sesión ha caducado, ingrese de nuevo"];
            }
            $validar_empresa= DB::table("tbl_empresas")->where("id_empresa",$user->id_empresa)->first();
            $pdfOutput = GastoPDF::generateFichaGasto($request->id_gasto, $validar_empresa, $user->nombre);

            // Retornar el PDF como respuesta al navegador
            return [ 'ok' => true, "data" => $pdfOutput ];
        }catch(\Exception | \PDOException $e){
            return ['ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage()];
        }        
    }

    public function search(Request $request) {
        try {
            $gastos = Gasto::select("id_gasto","folio","concepto_gasto","importe","fecha","status")
            ->when(!empty($request->fecha_inicial) && !empty($request->fecha_final), function($query) use ($request) {
                $query->whereBetween('fecha',[$request->fecha_inicial,$request->fecha_final]);
            })
            ->when(!empty($request->concepto_gasto),function($query) use ($request) {
                $query->where("concepto_gasto","like","%".$request->concepto_gasto."%");
            })
            ->when(!empty($request->admin_gastos_id), function($query) use ($request) {
                $query->where("admin_gastos_id",$request->admin_gastos_id);
            })            
            ->when(!empty($request->recibe_efectivo),function($query) use ($request) {
                $query->where("recibe_efectivo","like","%".strtoupper($request->recibe_efectivo)."%");
            })
            ->orderBy("folio","desc")
            ->get();
            $tabla_gastos = view('components.tables.table_reportes_gastos', compact('gastos'))->render();
            return ['ok' => true, "data" => $tabla_gastos];
        }catch(\Exception | \PDOException $e){
            return ['ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage()];
        }  
    }

    public function generateReport(Request $request) {
        try {
            if(session('user')) {
                $user = json_decode($this->decode_json(session('user')[0]));
            } else {
                return ['ok' => false, "message" => "Su sesión ha caducado, ingrese de nuevo"];
            }
            $validar_empresa= DB::table("tbl_empresas")->where("id_empresa",$user->id_empresa)->first();

            $gastos = Gasto::select("id_gasto","folio","concepto_gasto","importe","fecha","status","recibe_efectivo","tipo_comprobante","tblAG.nombre","descripcion")
            ->join("tbl_admin_gastos as tblAG","tblAG.id_admin_gastos","=","admin_gastos_id")
            ->when(!empty($request->fecha_inicial) && !empty($request->fecha_final), function($query) use ($request) {
                $query->whereBetween('fecha',[$request->fecha_inicial,$request->fecha_final]);
            })
            ->when(!empty($request->concepto_gasto),function($query) use ($request) {
                $query->where("concepto_gasto","like","%".$request->concepto_gasto."%");
            })
            ->when(!empty($request->admin_gastos_id), function($query) use ($request) {
                $query->where("admin_gastos_id",$request->admin_gastos_id);
            })            
            ->when(!empty($request->recibe_efectivo),function($query) use ($request) {
                $query->where("recibe_efectivo","like","%".strtoupper($request->recibe_efectivo)."%");
            })
            ->orderBy("folio","desc")
            ->get();
            
            $pdfOutput = GastoPDF::generateReport($gastos, $validar_empresa, $request);

            return ['ok' => true, "data" => $pdfOutput];
        }catch(\Exception | \Error | \PDOException $e){
            return ['ok' => false, "message" => "Ha ocurrido un error: ". $e->getMessage()];
        }  
    }
}
