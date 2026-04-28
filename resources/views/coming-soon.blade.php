<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCRE — Próximamente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center px-6 max-w-md">

        <div class="mb-8 flex justify-center">
            <div class="bg-yellow-100 rounded-full p-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-yellow-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3" />
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-3">Aplicación móvil en desarrollo</h1>

        <p class="text-gray-500 text-base leading-relaxed mb-10">
            Estamos trabajando para traerte la mejor experiencia.<br>
            Próximamente podrás descargar nuestra app y gestionar<br>
            tus finanzas desde tu dispositivo móvil.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Logout') }}
                </x-primary-button>
            </div>
        </form>

    </div>
</body>

</html>
