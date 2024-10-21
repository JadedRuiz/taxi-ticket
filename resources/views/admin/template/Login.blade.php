<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Sesión</title>
    @vite(['public/sass/app.scss', 'public/js/app.js', 'public/sass/login.scss', 'public/js/login.js'])
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
                <a href="./login" class="mb-3 d-block auth-logo">
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
                </div>
            </div>
        </div>
        <div class="my-3 text-center">
                <p>©<script>
                    document.write(new Date().getFullYear())
                </script> Reservacion App.
            </div>
    </section>
</body>
<script>
  window.routes = {
    'login' : '{{ route('admin.login') }}',
    'home' : '{{ route('admin.home') }}'
  }
 
  // You can access it like this
  let route = window.routes.users
</script>
</html>
