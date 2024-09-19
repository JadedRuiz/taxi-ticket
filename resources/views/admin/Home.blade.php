<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    @vite(['public/sass/app.scss','public/js/app.js','public/sass/admin.scss', 'public/js/admin.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css">
</head>
<body>
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="img-header d-flex">
                <div class="navbar-brand-box">
                    <a class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset($user->logo_path) }}" alt="" width="150">
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
                        <p class="my-0">{{ $user->nombre }}</p>
                        <span class="d-flex align-items-center"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                    </div>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}">
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
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.home') }}">
                            <span class="uil--home-alt"></span> &nbsp; Inicio</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <section class="contenido">
        <div class="page-content">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Página Inicio</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Admin</li>
                        <li class="breadcrumb-item active">Inicio</li>
                    </ol>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reservaciones</h4>
                            <p class="card-title-desc my-0">Esta tabla muestra la información de ultimas 100 reservaciones realizadas.</p>
                            <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped dataTable display" style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 100px;">Folio</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 300px;">Contacto</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 320px;" >Itinerario</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 98px;">Precio</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 183px;">Fecha Reserva</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 167px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservaciones as $reservacion)
                                                <tr>
                                                    <td>{{ $reservacion->folio }}</td>
                                                    <td>
                                                        {{ strtoupper($reservacion->nombre) }}
                                                        <br>
                                                        {{ $reservacion->telefono }}
                                                    </td>
                                                    <td>
                                                        1. {{ $reservacion->origen }} 
                                                        <br>
                                                        2. {{ $reservacion->destino }}
                                                    </td>
                                                    <td>{{ "$". number_format($reservacion->precio,2) }}</td>
                                                    <td>{{ date('d-m-Y H:m',strtotime($reservacion->date_creacion)) }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}"><i class="fa fa-print" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>                                            
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <button type="button" class="d-none btnModal" data-bs-toggle="modal" data-bs-target="#modalTicket"></button>
    <div class="modal fade" id="modalTicket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Previsulización de ticket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <object id="pdfShow" data="" width="100%" height="600px"/></object>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
  window.routes = {
    'generarTicket' : '{{ route('admin.generar') }}'
  }
</script>
</html>