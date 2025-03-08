<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Añadir Font Awesome para iconos -->
    <link rel="stylesheet" href="{{ asset('css/formLogin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4"
    data-validation-errors="{{ $errors->first() }}"
    data-error="{{ session('error') }}">
    <div class="flex w-full max-w-5xl container-shadow rounded-3xl overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
        <!-- Imagen izquierda -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-purple-600 items-center justify-center p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/50 to-purple-600/50 backdrop-blur-sm"></div>
            <img src="{{ asset('img/adje.png') }}" alt="Soporte Técnico" class="w-4/5 max-w-md relative z-10 hover:scale-105 transition-transform duration-300 drop-shadow-2xl">
        </div>

        <!-- Formulario derecho -->
        <div class="w-full lg:w-1/2 glass-effect p-8 lg:p-12">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-3 tracking-tight">¡Bienvenido!</h2>
                <p class="text-gray-600 text-lg">Inicia sesión en tu cuenta</p>
            </div>

            <form method="POST" action="{{ url('/') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-envelope text-400"></i>
                        </span>
                        <input type="email"
                               name="email"
                               id="email"
                               class="pl-12 w-full h-12 rounded-xl border-2 border-black-100 bg-white shadow-sm input-focus
                                      transition-all duration-200 hover:border-blue-300 focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-200 focus:outline-none"
                               placeholder="ejemplo@correo.com">
                    </div>
                    <span id="email-error" class="error text-red-500 text-sm mt-1 block"></span>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-lock text-400"></i>
                        </span>
                        <input type="password"
                               name="password"
                               id="password"
                               class="pl-12 w-full h-12 rounded-xl border-2 border-black-100 bg-white shadow-sm input-focus
                                      transition-all duration-200 hover:border-blue-300 focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-200 focus:outline-none"
                               placeholder="••••••••">
                    </div>
                    <span id="password-error" class="error text-red-500 text-sm mt-1 block"></span>
                </div>

                <button type="submit"
                        class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200"
                        id="btnSesion"
                        disabled>
                    Iniciar sesión
                </button>

                <div class="flex items-center justify-between mt-4 text-sm">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="remember"
                               id="remember"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-gray-700">
                            Recordar sesión
                        </label>
                    </div>
                    <a href="#" class="text-blue-600 hover:text-blue-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/validacionLogin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>
</body>
</html>
