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
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        var data_origen = "{{ env("APP_ORIGEN_ID") }}"
    </script>
</head>
<body class="open-sans-font">
    <div class="pantalla d-flex flex-column container-fluid position-relative" >
        <div class="row d-flex flex-column h-100 justify-content-between py-4" id="inicio">
            <div class="col-12 buscador-pantalla">
                <span class="btnClose"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center img-main">
                <img class="img-logo" src="{{ asset('/img/logo.png') }}">
            </div>
            <div class="col-12 text-center">
                <h2 class="title-pantalla mb-3">A Donde Vas?</h2>
                <p>Por favor ingresa el destino desado</p>
            </div>
            <div class="col-12 px-0 position-relative">
                <div class="contentMovement w-100 d-flex align-items-center flex-column">
                    <input class="form-control" type="text" name="" id="inpDir" placeholder="direccion">
                    <span class="btnSearch"><i class="fa fa-search" aria-hidden="true"></i></span>
                    <ul class="list-group list-default mt-1">
                        <li class="list-group-item">
                            <p class="my-0 py-0 text-center">NO SE ENCONTRARON DESTINOS</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn btn-siguiente d-flex align-items-center" id="btnSiguiente1">
                    Siguiente &nbsp;
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="row d-flex flex-column h-100 justify-content-between py-4 d-none" id="reserva">
            <div class="col-12 d-flex justify-content-center align-items-center img-main">
                <img class="img-logo" src="{{ asset('/img/auto.png') }}">
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <h2>ORIGEN</h2>
                <p class="sOrigen"></p>
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <h2>DESTINO</h2>
                <p class="sDestino"></p>
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <h2>TARIFA</h2>
                <p class="precio sPrecio"></p>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn btn-siguiente d-flex align-items-center" id="btnSiguiente2">
                    Siguiente&nbsp;
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="row d-flex flex-column h-100 justify-content-between py-4 d-none" id="formulario">
            <div class="col-12 d-flex justify-content-center img-main mt-3">
                <form class="w-100 needs-validation" novalidate>
                    <input type="hidden" class="form-control" id="iIdOrigen" name="iIdOrigen" placeholder="Ingrese su nombre">
                    <input type="hidden" class="form-control" id="iIdDestino" name="iIdDestino" placeholder="Ingrese su nombre">
                    <div class="form-group mt-2">
                        <label for="sNombre" class="text-center">Nombre</label>
                        <input type="text" class="form-control" id="sNombre" name="sNombre" placeholder="Ingrese su nombre" required>
                        <div class="invalid-feedback">
                            *Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="sTelefono" class="text-center">Teléfono</label>
                        <input type="number" class="form-control" id="sTelefono" placeholder="Ingrese su telefono" required>
                        <div class="invalid-feedback">
                            *Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="sCorreo" class="text-center">Correo Electrónico</label>
                        <input type="email" class="form-control" id="sCorreo" name="sCorreo" placeholder="Ingrese su correo" required>
                        <div class="invalid-feedback">
                            *Este campo es obligatorio
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn btn-siguiente d-flex align-items-center" id="btnReservar">
                    <div class="spinner-border text-light" role="status" style="display:none;"></div>
                    <div class="text-btn">Reservar</div>
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