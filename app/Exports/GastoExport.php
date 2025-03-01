<?php

namespace App\Exports;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Models\GastoModel as Gasto;
use DateTime;

class GastoExport {

    public static function generateFichaGasto($id_gasto, $empresa, $nombre) {
        $data = Gasto::where("id_gasto",$id_gasto)
        ->join("tbl_admin_gastos as tblAG","tblAG.id_admin_gastos","=","admin_gastos_id")
        ->first();
        $pdf = new Fpdf('P','mm','A4'); 
        $pdf->AddPage();
        //Import Fonts
        $pdf->AddFont('Raleway-Bold','','Raleway-Bold.php', public_path('fonts'));
        $pdf->AddFont('Raleway-Regular','','Raleway-Regular.php', public_path('fonts'));
        //Imagen
        $img_logo = str_replace('/img','',$empresa->logo_path);
        $pdf->Image(public_path('img').$img_logo,5,5,70,20,'PNG','');
        #region [Ficha Gasto]
            $pdf->setXY(5,30);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(50,10,"FECHA DEL GASTO","LTB",0,"L",1);
            $pdf->Cell(75,10,self::convertDateToText($data->fecha),"RTB",0,"C",1);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(35,10,"FOLIO","LTB",0,"R",1);
            $pdf->SetTextColor(34, 94, 205);
            $pdf->Cell(40,10,$data->folio,"RTB",0,"C",1);
            $pdf->setXY(5,40);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(20,10,"IMPORTE","LTB",0,"L",1);
            $pdf->Cell(40,10,$data->importe,"RTB",0,"C",1);
            $pdf->SetFont('Arial', '', 10);
            $importe_text = utf8_decode(self::convertNumberToText($data->importe));
            if(strlen($importe_text) > 68 ) {
                $pdf->MultiCell(140,5,"SON: ".$importe_text,"1",0,"L",1);
            }else {
                $pdf->Cell(140,10,"SON: ".$importe_text,"1",0,"L",1);
            }
            $pdf->setXY(5,50);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(35,10,"TIPO DE GASTO","LTB",0,"L",1);
            $pdf->SetFont('Arial', '', 10);
            $concepto = $data->concepto_gasto;
            if(strlen($concepto) > 30 ) {
                $concepto = strlen($concepto) > 57 ? substr($concepto,0,55)."..." : $concepto; 
                $pdf->MultiCell(60,5,$concepto,"1","C",1);
            }else {
                $pdf->Cell(60,10,$concepto,"1",0,"C",1);
            }
            $pdf->setXY(100,50);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50,5,"TIPO DE COMPROBANTE",1,0,"C",1);
            $pdf->setXY(100,55);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50,5,$data->tipo_comprobante,1,0,"C",1);
            $pdf->setXY(150,50);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(55,5,"FOLIO DE COMPROBANTE",1,0,"C",1);
            $pdf->setXY(150,55);
            $pdf->Cell(55,5,$data->folio_comprobante,1,0,"C",1);
            $pdf->setXY(5,60);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(200,10,utf8_decode($data->descripcion),"1","L",1);
            $pdf->setXY(5,$pdf->getY());
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(66,7,"ENTREGA EFECTIVO",1,0,"C",1);
            $pdf->Cell(67,7,"RECIBE EFECTIVO",1,0,"C",1);
            $pdf->Cell(67,7,"Vo. Bo.",1,0,"C",1);
            $pdf->setXY(5,$pdf->getY()+7);
            $pdf->Cell(66,25,"","TRL",0,"C",1);
            $pdf->Cell(67,25,"","TRL",0,"C",1);
            $pdf->Cell(67,25,"","TRL",0,"C",1);
            $pdf->setXY(5,$pdf->getY()+25);
            $pdf->SetFont('Arial', '', 9);
            $nombre_entrega = strlen($data->nombre) > 31 ? substr($data->nombre,0,31)."..." : $data->nombre;
            $nombre_recibe = strlen($data->recibe_efectivo) > 31 ? substr($data->recibe_efectivo,0,31)."..." : $data->recibe_efectivo;
            $pdf->Cell(66,7,utf8_decode($nombre_entrega),"BRL",0,"C",1);
            $pdf->Cell(67,7,utf8_decode($nombre_recibe),"BRL",0,"C",1);
            $pdf->Cell(67,7,"","BRL",0,"C",1);
            $pdf->setXY(5,$pdf->getY()+7);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(100,5,"Usuario imprimio: ".utf8_decode($nombre),1,0,"L",1);
            $pdf->Cell(100,5,utf8_decode("Fecha de impresión: ").date('d-m-Y'),1,0,"L",1);
        #endregion

        return base64_encode($pdf->Output('S','ficha_gasto.pdf'));
    }

    public static function generateReport($data, $empresa, $request) {
        $pdf = new Fpdf('P','mm','A4');
        $importe=0;
        $pdf->AddPage();
        $x = $request->bInfoDeta == "true" ? 1 : 5;
        //Imagen
        $img_logo = str_replace('/img','',$empresa->logo_path);
        $pdf->Image(public_path('img').$img_logo,$x,5,70,20,'PNG','');
        #region [Fechas]
            $pdf->SetFont('Arial', '', 11);
            $pdf->setXY($x,28);
            if(empty($request->fecha_inicial) && empty($request->fecha_final)) {
                $pdf->Cell(100,7,"FECHAS: SIN RANGOS DE FECHAS",0,0,"L");
            }else {
                $pdf->Cell(100,7,"FECHAS: ".self::formatFechaManual($request->fecha_inicial)." a ".self::formatFechaManual($request->fecha_final),0,0,"L");
            }
        #endregion
        #region [Tabla Simple]
            //Columnas
            if($request->bInfoDeta == "false") {
                $pdf->setXY($x,35);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(20,7,"FOLIO",1,0,"C");
                $pdf->Cell(140,7," CONCEPTO DEL GASTO",1,0,"L");
                $pdf->Cell(40,7,"IMPORTE",1,0,"C");
                $pdf->setXY(5,42);
                foreach ($data as $gasto) {
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetTextColor(34, 94, 205);
                    $pdf->Cell(20,7,$gasto->folio,"LB",0,"C");
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(140,7," ".$gasto->concepto_gasto,"B",0,"L");
                    $pdf->Cell(40,7,$gasto->importe,"RB",0,"C");
                    $pdf->setXY(5,$pdf->getY()+7);

                    if($pdf->GetY() >= 272) {
                        $pdf->AddPage();
                        $pdf->SetXY(5,10);
                        $pdf->SetFont('Arial', 'B', 11);
                        $pdf->Cell(20,7,"FOLIO",1,0,"C");
                        $pdf->Cell(140,7," CONCEPTO DEL GASTO",1,0,"L");
                        $pdf->Cell(40,7,"IMPORTE",1,0,"C");                  
                        $pdf->SetXY(5,17);
                    }
                }
            }
        #endregion
        #region [Tabla Detallada]
            if($request->bInfoDeta == "true") {
                $pdf->setXY($x,35);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(15,5,"FOLIO",1,0,"C");
                $pdf->Cell(15,5,"FECHA",1,0,"C");
                $pdf->Cell(45,5," CONCEPTO",1,0,"L");
                $pdf->Cell(25,5,"IMPORTE",1,0,"C");
                $pdf->Cell(20,5,"COMPROBA",1,0,"C");
                $pdf->Cell(28,5," RECIBE EFECTIVO",1,0,"L");
                $pdf->Cell(45,5,"DESCRIPCION",1,0,"L");
                $pdf->Cell(15,5,"STATUS",1,0,"C");
                $pdf->setXY($x,40);
                foreach ($data as $gasto) {
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetTextColor(34, 94, 205);
                    $pdf->Cell(15,5,$gasto->folio,"LB",0,"C");
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(15,5,$gasto->fecha,"B",0,"C");
                    $concepto_gasto = strlen($gasto->concepto_gasto) > 28 ? substr($gasto->concepto_gasto,0,28)."." : $gasto->concepto_gasto;
                    $pdf->Cell(45,5," ".$concepto_gasto,"B",0,"L");
                    $importe += (float)str_replace(['$', ',', ' '], '', $gasto->importe);
                    $pdf->Cell(25,5,$gasto->importe,"B",0,"C");
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(20,5,$gasto->tipo_comprobante,"B",0,"C");
                    $pdf->SetFont('Arial', '', 7);
                    $nombre = strlen($gasto->nombre) > 15 ? substr($gasto->nombre,0,17)."." : $gasto->nombre;
                    $pdf->Cell(28,5," ".$nombre,"B",0,"C");
                    $descripcion = strlen($gasto->descripcion) > 26 ? substr($gasto->descripcion,0,28)."." : $gasto->descripcion;
                    $pdf->Cell(45,5,$descripcion,"B",0,"L");
                    $status = strlen($gasto->status) > 8 ? substr($gasto->status,0,8)."." : $gasto->status;
                    $pdf->Cell(15,5,$status,"BR",0,"C");
                    $pdf->setXY($x,$pdf->getY()+5);

                    if($pdf->GetY()+5 >= 272) {
                        $pdf->AddPage();
                        $pdf->SetXY($x,10);
                        $pdf->Cell(15,5,"FOLIO",1,0,"C");
                        $pdf->Cell(15,5,"FECHA",1,0,"C");
                        $pdf->Cell(45,5," CONCEPTO",1,0,"L");
                        $pdf->Cell(25,5,"IMPORTE",1,0,"C");
                        $pdf->Cell(20,5,"COMPROBA",1,0,"C");
                        $pdf->Cell(28,5," RECIBE EFECTIVO",1,0,"L");
                        $pdf->Cell(45,5,"DESCRIPCION",1,0,"L");
                        $pdf->Cell(15,5,"STATUS",1,0,"C");                
                        $pdf->SetXY($x,15);
                    }
                }
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY($x,$pdf->GetY());
                $pdf->Cell(15,5,"",0,0,"C");
                $pdf->Cell(15,5,"",0,0,"C");
                $pdf->Cell(45,5," ",0,0,"L");
                $pdf->Cell(25,5,"$".number_format($importe,2),0,0,"C");
            }
        #endregion
        return base64_encode($pdf->Output('S','reporte.pdf'));
    }

    public static function formatFechaManual($fecha) {
        $meses = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];
        
        // Dividir la fecha
        list($dia, $mes, $anio) = explode('-', $fecha);
        
        return "$dia-" . $meses[$mes - 1] . "-$anio";
    }

    public static function convertDateToText($fecha) {
        // Crear un objeto DateTime con la fecha
        $date = new DateTime($fecha);

        // Obtener el día de la semana, día, mes y año
        $dias = ['Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado'];
        $meses = ['January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril', 'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'];

        $diaSemana = $dias[$date->format('l')]; // Traducir día de la semana
        $dia = $date->format('d');
        $mes = $meses[$date->format('F')]; // Traducir mes
        $anio = $date->format('Y');

        // Formatear la fecha como texto
        return "$diaSemana, $dia de $mes de $anio";
    }

    public static function convertNumberToText($numero) {
        $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        $decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $centenas = ['', 'cien', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];
        $especiales = [11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince'];
        
        // Definición de grandes números
        $miles = ['','mil','millón','mil millones'];

        // Quitar formato de moneda y convertir a número flotante
        $numero = str_replace(['$', ','], '', $numero);
        $partes = explode('.', $numero);
        $entero = (int)$partes[0];
        $decimal = isset($partes[1]) ? substr($partes[1], 0, 2) : '00';

        // Función recursiva para convertir números hasta 999
        $convertirTresCifras = function ($n) use ($unidades, $decenas, $centenas, $especiales, &$convertirTresCifras) {
            if ($n < 10) {
                return $unidades[$n];
            } elseif ($n < 20) {
                return $especiales[$n] ?? 'dieci' . $unidades[$n - 10];
            } elseif ($n < 100) {
                return $decenas[intdiv($n, 10)] . ($n % 10 > 0 ? ' y ' . $unidades[$n % 10] : '');
            } elseif ($n < 1000) {
                if ($n === 100) return 'cien';
                return $centenas[intdiv($n, 100)] . ' ' . $convertirTresCifras($n % 100);
            }
            return '';
        };

        // Convertir número entero a texto
        $resultado = '';
        $grupo = 0;
        
        while ($entero > 0) {
            $parte = $entero % 1000;
            if ($parte > 0) {
                $resultado = $convertirTresCifras($parte) . ' ' . $miles[$grupo] . ' ' . $resultado;
            }
            $entero = intdiv($entero, 1000);
            $grupo++;
        }

        // Eliminar el espacio extra final
        $resultado = trim($resultado);

        // Agregar denominación de moneda
        $resultado .= ' pesos';

        // Agregar los centavos
        if ($decimal !== '00') {
            $resultado .= ' con ' . $decimal . '/100 M.N.';
        } else {
            $resultado .= '';
        }
    
        return strtoupper(ucfirst($resultado));
    }       
}