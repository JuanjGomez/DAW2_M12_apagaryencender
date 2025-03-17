<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro - Sistema de Incidencias</title>
    <link rel="stylesheet" href="{{ asset('css/formLogin.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4"
    data-validation-errors="{{ $errors->first() }}"
    data-email-duplicado="{{ session('emailDuplicado') }}"
    data-error="{{ session('error') }}">

    <!-- Contenedor principal con flex-col en móvil -->
    <div class="flex flex-col lg:flex-row w-full max-w-4xl container-shadow rounded-3xl overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
        <!-- Imagen - visible arriba en móvil -->
        <div class="flex lg:w-1/2 bg-gradient-to-bl from-purple-600 to-blue-600 items-center justify-center p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-bl from-purple-600/50 to-blue-600/50 backdrop-blur-sm"></div>
            <img src="{{ asset('img/adje.png') }}" alt="Soporte Técnico"
                 class="w-40 lg:w-3/4 max-w-md relative z-10 hover:scale-105 transition-transform duration-300 drop-shadow-2xl">
        </div>

        <!-- Formulario -->
        <div class="w-full lg:w-1/2 glass-effect p-6 lg:p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2 tracking-tight">¡Regístrate!</h2>
                <p class="text-gray-600 text-base">Crea tu cuenta en el sistema</p>
            </div>

            <form action="{{ route('register') }}" method="post" class="space-y-4">
                @csrf
                <!-- Reducir el espacio entre campos y tamaño de inputs -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre usuario
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-user text-400 text-sm"></i>
                        </span>
                        <input type="text"
                               name="name"
                               id="name"
                               class="pl-10 w-full h-10 rounded-lg border-2 border-black-100 bg-white shadow-sm input-focus
                                      transition-all duration-200 hover:border-blue-300 focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-200 focus:outline-none text-sm"
                               placeholder="Nombre usuario">
                    </div>
                    <span id="name-error" class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Campo Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-envelope text-400 text-sm"></i>
                        </span>
                        <input type="email"
                               name="email"
                               id="email"
                               class="pl-10 w-full h-10 rounded-lg border-2 border-black-100 bg-white shadow-sm input-focus
                                      transition-all duration-200 hover:border-blue-300 focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-200 focus:outline-none text-sm"
                               placeholder="ejemplo@correo.com">
                    </div>
                    <span id="email-error" class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Campo Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-lock text-400 text-sm"></i>
                        </span>
                        <input type="password"
                               name="password"
                               id="password"
                               class="pl-10 w-full h-10 rounded-lg border-2 border-black-100 bg-white shadow-sm input-focus
                                      transition-all duration-200 hover:border-blue-300 focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-200 focus:outline-none text-sm"
                               placeholder="••••••••">
                    </div>
                    <span id="password-error" class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Campo Confirmar contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar contraseña
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-lock text-400 text-sm"></i>
                        </span>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="pl-10 w-full h-10 rounded-lg border-2 border-black-100 bg-white shadow-sm input-focus
                                      transition-all duration-200 hover:border-blue-300 focus:border-blue-500
                                      focus:ring-2 focus:ring-blue-200 focus:outline-none text-sm"
                               placeholder="••••••••">
                    </div>
                    <span id="password_confirmation-error" class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Campo Sede -->
                <div>
                    <label for="sede_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Sede
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-building text-400 text-sm"></i>
                        </span>
                        <select name="sede_id" id="sede_id" class="pl-10 w-full h-10 rounded-lg border-2 border-black-100 bg-white shadow-sm input-focus transition-all duration-200 hover:border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none text-sm">
                            <option selected disabled>Selecciona una sede</option>
                            @foreach ($sedes as $sede)
                            <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span id="sede_id-error" class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Botón de registro más compacto -->
                <button type="submit"
                        class="w-full py-2 px-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium rounded-lg transition duration-200 text-sm mt-4"
                        id="btnSesion"
                        disabled>
                    Crear cuenta
                </button>

                <!-- Enlace a login con menos espacio -->
                <div class="text-center mt-3">
                    <p class="text-xs text-gray-600">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                            Inicia sesión
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/validacionRegistro.js') }}"></script>
</body>
</html>
