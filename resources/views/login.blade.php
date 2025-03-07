<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Añadir Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Contenedor principal -->
        <div class="flex w-full max-w-5xl mx-4">
            <!-- Imagen izquierda -->
            <div class="hidden lg:flex lg:w-1/2 bg-blue-600 rounded-l-2xl items-center justify-center p-12">
                <img src="{{ asset('img/adje.png') }}" alt="Soporte Técnico" class="w-full max-w-md">
            </div>

            <!-- Formulario derecho -->
            <div class="w-full lg:w-1/2 bg-white p-8 lg:p-12 rounded-2xl lg:rounded-l-none shadow-lg">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">¡Bienvenido!</h2>
                    <p class="text-gray-600">Inicia sesión en tu cuenta</p>
                </div>

                <form method="POST" action="{{ url('/') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 p-4 rounded-lg">
                            <strong>{{ $errors->first() }}</strong>
                        </div>
                    @endif

                    <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
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
    </div>
    <script src="{{ asset('js/validacionLogin.js') }}"></script>
</body>
</html>
