<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrativo</title>
    
    @vite(['public/sass/app.scss','public/js/app.js'])
    @isset($entries)
        @vite($entries)  
    @endisset
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('41c544fd825ce344452a', {
            cluster: 'us3'
        });
    </script>
</head>
<body>
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="img-header d-flex">
                <div class="navbar-brand-box">
                    <a class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset($userData->logo_path) }}" alt="" width="150">
                        </span>
                    </a>
                </div>
            </div>
            <div class="d-flex">
                <div class="dropdown">
                    <div class="info-dropdown d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="img-admin">
                            <img src="{{ asset('img/logo-admin.png') }}" width="35">
                        </div>
                        <p class="my-0">{{ $userData->usuario }}</p>
                        <span class="d-flex align-items-center"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                    </div>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('auth.logout') }}">
                                <span class="uil--sign-out-alt"></span>
                                &nbsp; Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="top-nav">
            <nav class="navbar navbar-light navbar-expand-lg">
                <div class="collapse navbar-collapse" id="topnav-menu-content">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @foreach($userData->menu as $menu)
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route($menu->ruta) }}">
                                <span class="{{ $menu->icono }}"></span> &nbsp; {{ $menu->titulo }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <span class="navbar-text">
                        <strong>{{ date('d-m-Y h:i:s') }}</strong> 
                    </span>
                </div>
            </nav>
        </div>
    </header>
    <section class="contenido">
        {{ $slot }}
    </section>
    
    {{ $scripts }}
</body>
</html>