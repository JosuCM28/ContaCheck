<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago - Despacho Contable</title>
    <style>
        /* Reset y estilos base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background-color: #000000ff;
            color: white;
            padding: 5px;
            text-align: center;
        }
        
        .header img {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .payment-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        
        .detail-value {
            color: #212529;
        }
        
        .status-pagado {
            color: #28a745;
            font-weight: 600;
        }
        
        .status-pendiente {
            color: #ffc107;
            font-weight: 600;
        }
        
        .footer {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        
        .contact-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .contact-item {
            margin-bottom: 10px;
        }
        
        .thank-you {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .logo {
            display: block;
            margin: 0 auto 15px;
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="200" height="200" viewBox="0 0 212 212"><image width="212" height="212" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAE32lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4KPHg6eG1wbWV0YSB4bWxuczp4PSdhZG9iZTpuczptZXRhLyc+CjxyZGY6UkRGIHhtbG5zOnJkZj0naHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyc+CgogPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9JycKICB4bWxuczpBdHRyaWI9J2h0dHA6Ly9ucy5hdHRyaWJ1dGlvbi5jb20vYWRzLzEuMC8nPgogIDxBdHRyaWI6QWRzPgogICA8cmRmOlNlcT4KICAgIDxyZGY6bGkgcmRmOnBhcnNlVHlwZT0nUmVzb3VyY2UnPgogICAgIDxBdHRyaWI6Q3JlYXRlZD4yMDI1LTA4LTEyPC9BdHRyaWI6Q3JlYXRlZD4KICAgICA8QXR0cmliOkV4dElkPjY0N2U1MzYxLTIxODgtNDJlZC04YTY3LWJhYWU0NDM1ZGEzZDwvQXR0cmliOkV4dElkPgogICAgIDxBdHRyaWI6RmJJZD41MjUyNjU5MTQxNzk1ODA8L0F0dHJpYjpGYklkPgogICAgIDxBdHRyaWI6VG91Y2hUeXBlPjI8L0F0dHJpYjpUb3VjaFR5cGU+CiAgICA8L3JkZjpsaT4KICAgPC9yZGY6U2VxPgogIDwvQXR0cmliOkFkcz4KIDwvcmRmOkRlc2NyaXB0aW9uPgoKIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PScnCiAgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJz4KICA8ZGM6dGl0bGU+CiAgIDxyZGY6QWx0PgogICAgPHJkZjpsaSB4bWw6bGFuZz0neC1kZWZhdWx0Jz5CTSAtIDE8L3JkZjpsaT4KICAgPC9yZGY6QWx0PgogIDwvZGM6dGl0bGU+CiA8L3JkZjpEZXNjcmlwdGlvbj4KCiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0n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"></image><style>@media (prefers-color-scheme: light) { :root { filter: none; } }
@media (prefers-color-scheme: dark) { :root { filter: none; } }
</style></svg>
        </div>
        
        <div class="content">
            <div class="thank-you">¡Gracias por su pago!</div>
            
            <p>Estimado/a <span class="detail-value" id="nombre-cliente"> {{ $receipt->client->full_name }}</span>,</p>
            <p>Le confirmamos que hemos recibido su pago. A continuación encontrará los detalles de la transacción:</p>
            
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">Monto del pago:</span>
                    <span class="detail-value" id="monto">${{$receipt->mount}}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Método de pago:</span>
                    <span class="detail-value" id="metodo">
                     @switch($receipt->pay_method)
                        @case('EFECTIVO') Efectivo @break
                        @case('CHEQUE') Cheque @break
                        @case('TRANSFERENCIA') Transferencia @break
                    @endswitch

                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha del pago:</span>
                    <span class="detail-value" id="fecha">{{ \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value status-pagado" id="estado">@switch($receipt->status)
                        @case('PAGADO') Pagado @break
                        @case('PENDIENTE') Pendiente @break
                        @case('CANCELADO') Cancelado @break
                    @endswitch
</span>
                </div>
            </div>
            
            <p>Este correo sirve como comprobante de su transacción. Le recomendamos conservar esta información para sus registros.</p>
            
            <div class="contact-info">
                <h3>Información de contacto</h3>
                <div class="contact-item">
                    <strong>Despacho Contable Ejemplo</strong>
                </div>
                <div class="contact-item">
                    <strong>Teléfono:</strong> +52 226 316 1354 / +52 226 316 0629
                </div>
                <div class="contact-item">
                    <strong>Email:</strong> baltazarmontes77@prodigy.net.mx
                </div>
                <div class="contact-item">
                    <strong>Dirección:</strong> Av. Mariano Abasolo No. 37, Colonia Centro, Altotonga, Veracruz, México, 93700
                </div>
            </div>
            
            <p>Si tiene alguna pregunta sobre este pago o necesita asistencia adicional, no dude en ponerse en contacto con nosotros.</p>
        </div>
        
        <div class="footer">
            <div class="logo">Despacho Contable Baltazar Montes</div>
            <p>© 2025 Despacho Contable. Todos los derechos reservados.</p>
            <p>Este es un mensaje automático, por favor no responda a este correo.</p>
        </div>
    </div>
</body>
</html>
