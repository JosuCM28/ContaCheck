<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Recibo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="w-full max-w-7xl mx-auto my-6">
        <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">

            <!-- Alerta -->
            @switch($receipt->status)
                @case('PAGADO')
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-center" role="alert">
                        <strong class="font-bold">¡Recibo Verificado!</strong>
                        <span class="block sm:inline">Este recibo ha sido Pagado correctamente.</span>
                    </div>
                    @break

                @case('PENDIENTE')
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6 text-center" role="alert">
                        <strong class="font-bold">¡Recibo Pendiente!</strong>
                        <span class="block sm:inline">Este recibo está pendiente de pago.</span>
                    </div>
                    @break

                @case('CANCELADO')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 text-center" role="alert">
                        <strong class="font-bold">¡Recibo Cancelado!</strong>
                        <span class="block sm:inline">Este recibo ha sido cancelado.</span>
                    </div>
                    @break

                @default
                    <div class="bg-red-100 border border-red-400 text-gray-700 px-4 py-3 rounded relative mb-6 text-center" role="alert">
                        <strong class="font-bold">¡Recibo Indisponible!</strong>
                        <span class="block sm:inline">El estado del recibo no es válido o no está definido.</span>
                    </div>
                    @break
            @endswitch

            <!-- Título -->
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 border-b pb-4 mb-6 text-center sm:text-left">Detalles del Recibo</h2>

            <!-- Detalles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $fields = [
                        'Tipo de Recibo' => $receipt->category->name,
                        'Realizado por' => $receipt->counter->full_name,
                        'Contribuyente' => $receipt->client->full_name,
                        'Método de Pago' => ucfirst($receipt->pay_method),
                        'Monto $MXN' => '$' . number_format($receipt->mount, 2),
                        $receipt->category->description => $receipt->concept,
                        'Fecha de Pago' => \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y'),
                        'Estado' => ucfirst($receipt->status),
                        'Identificador' => $receipt->identificator
                    ];
                @endphp

                @foreach($fields as $label => $value)
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-600">{{ $label }}</label>
                        <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                            <p class="text-gray-800 font-medium text-sm sm:text-base break-words">{{ $value }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</body>
</html>
