<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mi taxi</title>
    @vite(['public/sass/app.scss', 'public/sass/viaje.scss', 'public/js/app.js'])
</head>
<body class="open-sans-font">
    <div class="pantalla d-flex flex-column container-fluid position-relative" >
        <div class="row d-flex flex-column h-100 justify-content-start py-4">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <img class="img-logo" src="{{ asset('/img/buen-trabajo.png') }}">
            </div>
            <div class="col-12 mt-4">
                <p class="title-reserva">¡Su reserva se ha realizado exitosamente!</p>
            </div>
            <div class="col-12 mt-5">
                <p class="subtitle-reserva">La información de su reserva fue enviada al correo electrónico proporcionado.</p>
            </div>
        </div>
        <div class="tabs d-flex flex-row justify-content-center py-3">
            <a class="btn btn-siguiente d-flex align-items-center" href="{{ route('reserva.index') }}">
                Nueva Reserva
            </a>
        </div>
    </div>
</body>
</html>