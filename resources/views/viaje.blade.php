<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mi taxi</title>
    @vite(['public/sass/app.scss', 'public/sass/viaje.scss', 'public/js/app.js', 'public/js/viaje.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="open-sans-font">
    <div class="pantalla d-flex flex-column container-fluid" >
        <div class="row d-flex flex-column h-100 justify-content-between py-4" id="inicio">
            <div class="col-12 d-flex justify-content-center img-main">
                <img src="{{ asset('public/img/logo.png') }}">
            </div>
            <div class="col-12 text-center">
                <h2 class="title-pantalla">A Donde Vas?</h2>
            </div>
            <div class="col-12 text-center">                
                <p>por favor ingresa el destino desado</p>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <input type="search" name="" id="inpDir" placeholder="direccion">
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn-siguiente d-flex align-items-center" id="btnSiguiente1">
                    Siguiente &nbsp;
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="row d-flex flex-column h-100 justify-content-between py-4 d-none" id="reserva">
            <div class="col-12 d-flex justify-content-center img-main">
                <img src="{{ asset('public/img/logo.png') }}">
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <h2>ORIGEN</h2>
                <p>TERMINAL DE AUTOBUSES ADO</p>
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <h2>DESTINO</h2>
                <p>CENTRO DE CONVENCIONES YUCATAN SIGLO XXI</p>
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <h2>TARIFA</h2>
                <p class="precio">$ 185.00</p>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn-siguiente d-flex align-items-center" id="btnSiguiente2">
                    Siguiente&nbsp;
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="row d-flex flex-column h-100 justify-content-between py-4 d-none" id="formulario">
            <div class="col-12 d-flex justify-content-center img-main mt-3">
                <div class="form w-100">
                    <input type="hidden" class="form-control" id="iIdDirOrigen" name="iIdDirOrigen" placeholder="Ingrese su nombre">
                    <div class="form-group mt-2">
                        <label for="sNombre" class="text-center">NOMBRE</label>
                        <input type="text" class="form-control" id="sNombre" name="sNombre" placeholder="Ingrese su nombre">
                    </div>
                    <div class="form-group mt-2">
                        <label for="sTelefono" class="text-center">TELEFONO</label>
                        <input type="email" class="form-control" id="sTelefono" placeholder="Ingrese su telefono">
                    </div>
                    <div class="form-group mt-2">
                        <label for="sCorreo" class="text-center">CORREO ELECTRONICO</label>
                        <input type="email" class="form-control" id="sCorreo" name="sCorreo" placeholder="Ingrese su correo">
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn-siguiente d-flex align-items-center" id="btnReservar">
                    Reservar
                </button>
            </div>
        </div>
        <div class="tabs d-flex flex-row justify-content-center">
            <span id="ico1" class="span-active"><i class="fa fa-circle" aria-hidden="true"></i></span>
            <span id="ico2" class="mx-3"><i class="fa fa-circle" aria-hidden="true"></i></span>
            <span id="ico3"><i class="fa fa-circle" aria-hidden="true"></i></span>
        </div>
    </div>
</body>
</html>