
<x-layout_login  :entries="$entries">
    <form>
        <div class="mb-3">
            <label class="form-label" for="user">Usuario</label>
            <input type="text" class="form-control " name="email" value="" id="user" placeholder="Ingresa tu usuario">
        </div>
        <div class="mb-3">
            <div class="float-end">
                <a href="http://minible-h-light.laravel.themesbrand.com/password/reset" class="text-muted">Olvidaste tu contraseña?</a>
            </div>
            <label class="form-label" for="pass">Contraseña</label>
            <input type="password" class="form-control " value="" name="pass" id="pass" placeholder="Ingresa tu contraseña">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="auth-remember-check" name="remember">
            <label class="form-check-label" for="auth-remember-check">Recuerda me</label>
        </div>
        <small class="alert-dir d-flex justify-content-center w-100 d-none">
            Error: Es necesario seleccionar una dirección
        </small>
        <div class="mt-3 text-end">
            <button id="btn-loading" class="btn btn-primary w-sm waves-effect waves-light d-none">
                <i class="fa fa-check d-none" aria-hidden="true"></i>
                <div class="spinner-border text-light" role="status"></div>
                <div class="loading-text">Entrando</div>
            </button>
            <a id="submitForm" class="btn btn-primary w-sm waves-effect waves-light">Entrar</a>
        </div>
    </form>
    <x-slot name="scripts">
        <script>
            window.routes = {
                'login' : '{{ route('auth.login') }}',
                'home' : '{{ route('admin.home') }}'
            }
        </script>
    </x-slot> 
</x-layout>  