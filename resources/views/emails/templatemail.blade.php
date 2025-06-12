<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago - Despacho Contable Baltazar Montes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 0 0 10px;
        }
        .content ul {
            list-style: none;
            padding: 0;
        }
        .content ul li {
            margin: 5px 0;
            font-size: 14px;
        }
        .content ul li strong {
            color: #555;
        }
        .highlight {
            background-color: #e0f7fa;
            padding: 10px;
            border: 1px solid #b2ebf2;
            border-radius: 4px;
            margin: 10px 0;
            font-weight: bold;
            text-align: center;
        }
        .footer {
            background-color: #f1f1f1;
            color: #888;
            font-size: 12px;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recibo de Pago Verificado</h1>
        </div>

        <div class="content">
            <p>Estimado/a <strong>{{ $receipt->client->name }}</strong>,</p>

            <p>Gracias por su pago. Hemos recibido su transacción y le enviamos su recibo oficial con los detalles a continuación:</p>

            <div class="highlight">
                Código de Recibo: <strong> #{{ $receipt->id }}</strong>
            </div>

            <ul>
                <li><strong>Cliente:</strong> {{ $receipt->client->full_name }}</li>
                <li><strong>Contador Responsable:</strong> {{ $receipt->counter->full_name }}</li>
                <li><strong>Monto:</strong> ${{ number_format($receipt->mount, 2) }} MXN</li>
                <li><strong>Método de Pago:</strong> 
                    @switch($receipt->pay_method)
                        @case('EFECTIVO') Efectivo @break
                        @case('CHEQUE') Cheque @break
                        @case('TRANSFERENCIA') Transferencia @break
                    @endswitch
                </li>
                <li><strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y') }}</li>
                <li><strong>Fecha de Pago:</strong> {{ \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y') }}</li>
                <li><strong>Estado:</strong> 
                    @switch($receipt->status)
                        @case('PAGADO') Pagado @break
                        @case('PENDIENTE') Pendiente @break
                        @case('CANCELADO') Cancelado @break
                    @endswitch
                </li>
            </ul>

            <p>Si tiene alguna consulta sobre su recibo, no dude en contactarnos. Estamos aquí para ayudarle.</p>

        </div>

        <div class="footer">
            <p><strong>Despacho Contable Baltazar Montes</strong></p>
            <p>Teléfono: (+52) 226-316-13-54 | Email: baltazarmontes77@prodigy.net.mx</p>
            <p>Este es un correo automático, por favor no lo responda.</p>
        </div>
    </div>
</body>
</html>