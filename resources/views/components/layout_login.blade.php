<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inicio Sesión</title>
    @vite(['public/sass/app.scss','public/js/app.js'])
    @isset($entries)
        @vite($entries)  
    @endisset
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <section class="container section-login d-flex flex-column justify-content-center align-items-center">
        <div class="col-lg-12">
            <div class="text-center">
                <a href="{{ route('index') }}" class="mb-3 d-block auth-logo">
                    <img src="{{ asset('/img/logo_empresa.png') }}" class="logo-img"  class="logo logo-dark">
                </a>
            </div>
        </div>
        <div class="card py-3">
            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Bienvenido !</h5>
                    <p class="text-muted">Inicia sesión al panel admin.</p>
                </div>
                <div class="p-2 mt-4">
                    <section class="">
                        {{ $slot }}
                    </section>
                </div>
            </div>
        </div>
        <div class="my-3 text-center">
                <p>©<script>
                    document.write(new Date().getFullYear())
                </script> Reservacion App.
            </div>
    </section>
    {{ $scripts }}
</body>
</html>