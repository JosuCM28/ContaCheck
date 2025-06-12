<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Recibo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">

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

            <!-- Detalles del Recibo -->
            <h2 class="text-2xl font-semibold text-gray-800 border-b pb-4 mb-6">Detalles del Recibo</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tipo de Recibo -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Tipo de Recibo</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ $receipt->category->name }}</p>
                    </div>
                </div>

                <!-- Realizado por -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Realizado por</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ $receipt->counter->full_name }}</p>
                    </div>
                </div>

                <!-- Contribuyente -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Contribuyente</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ $receipt->client->full_name }}</p>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div>
                    <label class="block text-sm font-medium text-gray-600">Método de Pago</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ ucfirst($receipt->pay_method) }}</p>
                    </div>
                </div>

                <!-- Monto -->
                <div>
                    <label class="block text-sm font-medium text-gray-600">Monto $MXN</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">${{ number_format($receipt->mount, 2) }}</p>
                    </div>
                </div>

                <!-- Concepto -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">{{ $receipt->category->description }}</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ $receipt->concept }}</p>
                    </div>
                </div>

                <!-- Fecha de Pago -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Fecha de Pago</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Estado -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Estado</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ ucfirst($receipt->status) }}</p>
                    </div>
                </div>

                <!-- Identificador -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Identificador</label>
                    <div class="mt-2 bg-gray-50 p-3 rounded-md border">
                        <p class="text-gray-800 font-medium">{{ $receipt->identificator }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
