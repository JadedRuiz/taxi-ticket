<?php

namespace App\Exports;

use Codedge\Fpdf\Fpdf\Fpdf;

class TicketExport {

    public static function generarTicket($viaje, $det_viaje, $destino, $origen, $empresa, $data_vehiculo_operador = null) {
        if($data_vehiculo_operador != null) {
            $pdf = new Fpdf('P','mm', array(80,150));
        }else {
            $pdf = new Fpdf('P','mm', array(80,140)); 
        }
        $pdf->AddPage();
        //Imagen
        $img_logo = str_replace('/img','',$empresa->logo_path);
        $pdf->Image(public_path('img').$img_logo,5,5,40,15,'PNG','');
        //Importamos los fonts
        $pdf->AddFont('Raleway-Bold','','Raleway-Bold.php', public_path('fonts'));
        $pdf->AddFont('Raleway-Regular','','Raleway-Regular.php', public_path('fonts'));
        //General
        $pdf->setXY(5,22);
        $pdf->SetFont('Raleway-Bold', '', 8);
        $pdf->Cell(70,4,"GENERAL",0,0,"L");
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(0, 0, 0);
        #region [Division]
            $pdf->setXY(5,26);
            $pdf->Cell(70,0.05,"",0,0,"",1);
        #endregion
        #region [Area General]
            $pdf->SetFont('Raleway-Regular', '', 7);
            $pdf->setXY(5,28);
            $pdf->Cell(30,4,"Titulo",0,0,"L");
            $pdf->Cell(40,4,$viaje->nombre_viaje,0,0,"L");
            $pdf->setXY(5,31);
            $pdf->Cell(30,4,"Estatus",0,0,"L");
            $pdf->Cell(40,4,$viaje->status,0,0,"L");
            $pdf->setXY(5,34);
            $pdf->Cell(30,4,"Tipo de servicio",0,0,"L");
            $pdf->Cell(40,4,$viaje->tipo_servicio,0,0,"L");
            $pdf->setXY(5,37);
            $pdf->Cell(30,4,"Tipo de viaje",0,0,"L");
            $pdf->Cell(40,4,$viaje->tipo_viaje,0,0,"L");
            $pdf->setXY(5,40);
            $pdf->Cell(30,4,"Fecha y hora de reserva",0,0,"L");
            $pdf->Cell(40,4,$viaje->date_creacion,0,0,"L");
            $pdf->setXY(5,43);
            $pdf->Cell(30,4,"Costo del servicio",0,0,"L");
            $pdf->Cell(40,4,"$".number_format($destino->precio ?? '0.00',2),0,0,"L");
            $pdf->setXY(5,46);
            $pdf->Cell(30,4,"Distancia",0,0,"L");
            if(isset($destino->distancia)){
                $pdf->Cell(40,4,$destino->distancia > 0 ? $destino->distancia." Km" : $destino->distancia ,0,0,"L");
            }else {
                $pdf->Cell(40,4,"0 Km" ,0,0,"L");
            }
            
            $pdf->setXY(5,49);
            $pdf->Cell(30,4,"Duracion",0,0,"L");
            $pdf->Cell(40,4,$destino->duracion ?? '0:00',0,0,"L");
            $pdf->setXY(5,52);
            $pdf->Cell(30,4,"Comentarios",0,0,"L");
            $pdf->Cell(40,4,$viaje->comentarios,0,0,"L");
        #endregion
        #region [Titulo Itinerario]
            $pdf->SetFont('Raleway-Bold', '', 8);
            $pdf->setXY(5,58);
            $pdf->Cell(70,4,"ITINERARIO",0,0,"L");   
            $pdf->setXY(5,62);      
            $pdf->Cell(70,0.05,"",0,0,"",1);
        #endregion
        #region [Cuerpo Itinerario]
        if(isset($destino) && isset($origen)){
            $pdf->SetFont('Raleway-Regular', '', 7);
            $pdf->setXY(5,64);
            $pdf->Cell(70,4,"1. ".$origen->origen,0,0,"L");
            $pdf->setXY(5,67);
            $pdf->Cell(70,4,"2. ".$destino->destino,0,0,"L");
        }            
        #endregion
        #region [Titulo Vehiculo]
            $pdf->SetFont('Raleway-Bold', '', 8);
            $pdf->setXY(5,73);
            if($data_vehiculo_operador != null ) {
                $pdf->Cell(70,4,"VEHICULO & OPERADOR",0,0,"L");   
            } else {
                $pdf->Cell(70,4,"VEHICULO",0,0,"L");   
            }
            $pdf->setXY(5,77);      
            $pdf->Cell(70,0.05,"",0,0,"",1);
        #endregion
        #region [Cuerpo Vehiculo]
            if($data_vehiculo_operador != null ) {
                $pdf->SetFont('Raleway-Regular', '', 7);
                $pdf->setXY(5,79);
                $pdf->Cell(30,4,"Vehiculo y Marca",0,0,"L");
                $pdf->Cell(40,4,$data_vehiculo_operador->vehiculo.", ".$data_vehiculo_operador->marca." (".$data_vehiculo_operador->modelo.")",0,0,"L");
                $pdf->setXY(5,82);
                $pdf->Cell(30,4,"No. max de maletas",0,0,"L");
                $pdf->Cell(40,4,$det_viaje->no_maletas,0,0,"L");
                $pdf->setXY(5,85);
                $pdf->Cell(30,4,"No. max de pasajeros",0,0,"L");
                $pdf->Cell(40,4,$det_viaje->no_pasajeros,0,0,"L");
                $pdf->setXY(5,88);
                $pdf->Cell(30,4,"Nombre operador",0,0,"L");
                $nombre = $data_vehiculo_operador->nombres." ".$data_vehiculo_operador->apellidos;
                strlen($nombre) > 23 ? substr($nombre, 0,23) : null;
                $pdf->Cell(40,4,$nombre,0,0,"L");
            }else {
                $pdf->SetFont('Raleway-Regular', '', 7);
                $pdf->setXY(5,79);
                $pdf->Cell(30,4,"Marca y Tipo",0,0,"L");
                $pdf->Cell(40,4,$det_viaje->vehiculo,0,0,"L");
                $pdf->setXY(5,82);
                $pdf->Cell(30,4,"No. max de maletas",0,0,"L");
                $pdf->Cell(40,4,$det_viaje->no_maletas,0,0,"L");
                $pdf->setXY(5,85);
                $pdf->Cell(30,4,"No. max de pasajeros",0,0,"L");
                $pdf->Cell(40,4,$det_viaje->no_pasajeros,0,0,"L");
            }            
        #endregion
        #region [Titulo Det Cliente]
            $pdf->SetFont('Raleway-Bold', '', 8);
            $pdf->setXY(5,94);
            $pdf->Cell(70,4,"DETALLES DEL CLIENTE",0,0,"L");   
            $pdf->setXY(5,98);      
            $pdf->Cell(70,0.05,"",0,0,"",1);
        #endregion
        #region [Cuerpo Det Cliente]
            $pdf->SetFont('Raleway-Regular', '', 7);
            $pdf->setXY(5,100);
            $pdf->Cell(30,4,"Nombre completo",0,0,"L");
            $pdf->Cell(40,4,utf8_decode($det_viaje->nombre),0,0,"L");
            $pdf->setXY(5,103);
            $pdf->Cell(30,4,utf8_decode("Correo electrónico"),0,0,"L");
            $pdf->Cell(40,4,$det_viaje->correo,0,0,"L");
            $pdf->setXY(5,106);
            $pdf->Cell(30,4,utf8_decode("Teléfono"),0,0,"L");
            $pdf->Cell(40,4,$det_viaje->telefono,0,0,"L");
        #endregion
        #region [Titulo Det Pago]
            $pdf->SetFont('Raleway-Bold', '', 8);
            $pdf->setXY(5,111);
            $pdf->Cell(70,4,"TIPO DE PAGO",0,0,"L");   
            $pdf->setXY(5,115);      
            $pdf->Cell(70,0.05,"",0,0,"",1);
        #endregion
        #region [Cuerpo Pago]
            $pdf->SetFont('Raleway-Regular', '', 7);
            $pdf->setXY(5,117);
            $pdf->Cell(30,4,"Pago",0,0,"L");
            $pdf->Cell(40,4,$det_viaje->tipo_pago,0,0,"L");
        #endregion
        return base64_encode($pdf->Output('S','ticket.pdf'));
    }
}