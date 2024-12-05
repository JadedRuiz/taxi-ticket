<x-layout :user-data="$user"  :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página Reportes</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active" id="prueba">Reportes</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="title-cardbody d-flex justify-content-between">
                            <h4 class="card-title">Reportes</h4>
                        </div>
                        <p class="card-title-desc my-0">Generá los reportes de tus ventas entre otras cosas.</p>
                        <form class="filtros d-flex align-items-end mt-3">
                            <div class="form-group">
                                <label class="lblInp">Desglose por:</label>
                                <select class="form-select" id="desglose">
                                    <option value="1" selected>General</option>
                                    <option value="2">Caja</option>
                                    <option value="3">Operador</option>
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="Folio" id="folio" name="folio" maxlength="50"> -->
                            </div>
                            <div class="form-group">
                                <label class="lblInp" for="fechas">Seleccione las fechas:</label>
                                <div class="fechas d-flex align-items-center" id="fechas">
                                    <input type="text" class="form-control " name="email" value="" id="user" placeholder="dd/mm/yyyy">
                                    <span class="mx-1">a</span>
                                    <input type="text" class="form-control " name="email" value="" id="user" placeholder="dd/mm/yyyy">
                                </div>
                                
                            </div>
                            <div class="btn btn-success">buscar</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            // window.routes = {
            //     'generarTicket' : '{{ route('admin.api.generar') }}',
            //     'agregarNuevoTurno' : '{{ route('admin.api.agregarNuevoTurno') }}',
            //     'eliminarTurno' : '{{ route('admin.api.eliminarTurno') }}',
            //     'obtenerTurnosAsync' : '{{ route('admin.api.obtenerTurnosAsync') }}',
            //     'asignarOperadorAViaje' : '{{ route('admin.api.asignarOperadorAViaje') }}',
            //     'asignarOperadorAViajeAdmin' : '{{ route('admin.api.asignarOperadorAViajeAdmin') }}',
            //     'inicioOperacion' : '{{ route('auth.inicioOperacion') }}',
            //     'listaResultados' : '{{ route('auth.listaResultados') }}',
            //     'cierreOperacion' : '{{ route('auth.cierreOperacion') }}',
            //     'cancelarViaje' : '{{ route('admin.api.cancelarViaje') }}',
            //     'obtenerReservasCaja' : '{{ route('admin.api.obtenerReservasCaja') }}'
            // }
            // window.user = @json($user);
        </script>
    </x-slot> 
</x-layout>  
