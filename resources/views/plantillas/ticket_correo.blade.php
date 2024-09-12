<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Exitosa</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        table { border: 0px solid transparent; border-width: 0px; }
         /* CLIENT-SPECIFIC STYLES */
         body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        /* RESET STYLES */
        img { border: 0; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        }
        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] { margin: 0 !important; }
        /* MEDIA QUERIES */
        @media all and (max-width:639px){
        .wrapper{ width:320px!important; padding: 0 !important; }
        .container{ width:300px!important;  padding: 0 !important; }
        .mobile{ width:300px!important; display:block!important; padding: 0 !important; }
        .img{ width:100% !important; height:auto !important; }
        *[class="mobileOff"] { width: 0px !important; display: none !important; }
        *[class*="mobileOn"] { display: block !important; max-height:none !important; }
    </style>
</head>
<body>
    <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#EEEEEE" style="font-family:Arial;font-size:15px;color:#777777;line-height:150%">
        <tbody>
            <tr height="50px"><td style="padding:0px"></td></tr>
            <tr>
                <td style="padding: 0px;">
                    <table class="mobile" cellspacing="0" cellpadding="0" width="600px" border="0" align="center" bgcolor="#FFFFFF" style="border:solid 1px #e1e8ed;padding:50px">
                        <tbody>
                            <tr>
                                <td style="padding: 0px;" class="mobile">
                                    <img class="img" src="https://futvyucatan.com/wp-content/uploads/2019/07/futv-sindicato-taxistas-yucatan-mobile.png" alt="">
                                    <br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 0px 5px 0px;font-weight:bold;color:#444444;border-bottom:dotted 1px #aaaaaa;text-transform:uppercase">General</td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:15px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px">
                                    <table cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Titulo</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $viaje->nombre_viaje }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Estatus</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">Pendiente</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Tipo de servicio</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $viaje->tipo_servicio }}</td>
                                            </tr>	
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Tipo de viaje</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $viaje->tipo_viaje }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Fecha y hora de reserva</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ date("d-m-Y h:m:s", strtotime($viaje->date_creacion)); }} </td>
                                            </tr>	
                                            
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Cantidad total del pedido</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ "$".number_format($destino->precio,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Distancia</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top"> {{ $destino->distancia }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Duración</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top"> {{ $destino->duracion }}</td>
                                            </tr>	
                                            
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Comentarios</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $viaje->comentarios }}</td>
                                            </tr>   
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:30px"></td>
                            </tr>
                            <tr>
								<td style="padding:0px 0px 5px 0px;font-weight:bold;color:#444444;border-bottom:dotted 1px #aaaaaa;text-transform:uppercase">Itinerario</td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:15px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px">
                                    <table cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="padding:0px">
                                                    <ol style="margin:0px;padding:0px;list-style-position:inside">
                                                        <li style="margin:0px;padding:0px"><a href="">{{ $origen->origen }}</a></li>
                                                        <li style="margin:0px;padding:0px"><a href="">{{ $destino->destino }}</a></li>
                                                    </ol>
                                                </td>
                                            </tr>   
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:30px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px 0px 5px 0px;font-weight:bold;color:#444444;border-bottom:dotted 1px #aaaaaa;text-transform:uppercase">Vehiculo</td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:15px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px">
                                    <table cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Marca y Tipo</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">Por asignar</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Numero maximo de maletas</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $det_viaje->no_maletas }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Número maximo de pasajeros</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $det_viaje->no_pasajeros }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:30px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px 0px 5px 0px;font-weight:bold;color:#444444;border-bottom:dotted 1px #aaaaaa;text-transform:uppercase">Detalles del cliente</td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:15px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px">
                                    <table cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Nombre</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $det_viaje->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Correo</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top"><a href="{{ $det_viaje->correo }}" target="_blank">{{ $det_viaje->correo }}</a></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Telefono</td>
                                                <td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $det_viaje->telefono }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:30px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px 0px 5px 0px;font-weight:bold;color:#444444;border-bottom:dotted 1px #aaaaaa;text-transform:uppercase">Tipo de Pago</td>
                            </tr>
                            <tr>
                                <td style="padding:0px;height:15px"></td>
                            </tr>
                            <tr>
                                <td style="padding:0px">
                                    <font color="#888888"></font>
                                    <font color="#888888"></font>
                                    <table cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
												<td style="padding:0px 5px 0px 0px;width:250px;vertical-align:top">Pago</td>
												<td style="padding:0px 0px 0px 5px;width:300px;vertical-align:top">{{ $det_viaje->tipo_pago }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <font color="#888888"></font>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr height="50px"><td style="padding:0px"></td></tr>
        </tbody>
    </table>
</body>
</html>