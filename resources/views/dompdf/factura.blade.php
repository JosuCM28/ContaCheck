<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Recibo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .invoice-box {
            width: 100%;
            max-width: 190mm;
            margin: auto;
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 2px;
            vertical-align: top;
        }

        h3 {
            margin: 2px 0;
            font-size: 10px;
        }

        .heading td {
            background: #eee;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            text-align: center;
        }

        .item td {
            border-bottom: 1px solid #eee;
        }

        .totals td {
            font-weight: bold;
            text-align: right;
        }

        .text-right {
            text-align: right;
        }

        img.logo {
            max-width: 70px;
            height: auto;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 6px;
        }

        .qr-code {
            text-align: center;
            margin-top: 10px;
        }

        .bg-gray {
            background-color: #cfd4e6;
            font-weight: bold;
            padding: 2px;
        }

        .mini-table td {
            padding: 1px 3px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">

        <table>
            <tr>
                <td style="width: 50%">
                    @php
                        $path = public_path('img/fondodesp.png');
                        $imageData = base64_encode(file_get_contents($path));
                        $src = 'data:image/png;base64,' . $imageData;
                    @endphp
                    <img src="{{ $src }}" class="logo" alt="Logo">
                </td>
                <td class="text-right">
                    <p><strong>COMPROBANTE FISCAL DIGITAL POR INTERNET</strong></p>
                    <p>CFDI v4.0</p>
                    <p><strong>Fecha de Emisión:</strong> {{ $receipt->payment_date }}</p>
                    <p><strong>Identificador:</strong> {{ $receipt->identificator }}</p>
                    <p><strong>Tipo de Comprobante:</strong> {{ $receipt->category->name }}</p>
                    <p><strong>Expedido en:</strong> {{ $receipt->counter->cp }}</p>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td>
                    <h3 style="color:blue">EMISOR DEL COMPROBANTE FISCAL</h3>
                    <p><strong>RFC:</strong> {{ $receipt->counter->rfc }}</p>
                    <p><strong>Nombre:</strong> {{ $receipt->counter->full_name }}</p>
                    <p><strong>Régimen Fiscal:</strong> {{ $receipt->counter->regime->title }}</p>
                    <p><strong>Domicilio:</strong> {{ $receipt->counter->address }}</p>
                </td>
                <td>
                    <h3 style="color:red">RECEPTOR</h3>
                    <p><strong>RFC:</strong> {{ $receipt->client->rfc }}</p>
                    <p><strong>Nombre:</strong> {{ $receipt->client->full_name }}</p>
                    <p><strong>C.P.:</strong> {{ $receipt->client->cp }}</p>
                    <p><strong>Régimen Fiscal:</strong> {{ $receipt->client->regime->title }}</p>
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr class="heading">
                    <td>Código</td>
                    <td>Clave SAT</td>
                    <td>Descripción</td>
                    <td>Unidad</td>
                    <td>Cant</td>
                    <td>Precio</td>
                    <td>Importe</td>
                </tr>
            </thead>
            <tbody>
                <tr class="item">
                    <td>7501059200050</td>
                    <td>95121913</td>
                    <td>
                        ALIAT CUATRIMESTRE MAESTRÍA<br>
                        IVA 16% = 24.00<br>
                        ISR 10% = 15.00
                    </td>
                    <td>M36</td>
                    <td>1.00</td>
                    <td>$1,450.00</td>
                    <td>$1,450.00</td>
                </tr>
            </tbody>
        </table>

        <table style="margin-top: 6px;">
            <tr>
                <td style="width: 60%">
                    <p><strong>Forma de Pago:</strong> 01 - Efectivo</p>
                    <p><strong>Método de Pago:</strong> PUE</p>
                    <p><strong>Condiciones:</strong> Contado</p>
                    <p><strong>Moneda:</strong> MXN - TC: 1.00</p>
                </td>
                <td>
                    <table class="mini-table">
                        <tr>
                            <td style="text-align:right">Subtotal:</td>
                            <td style="text-align:right">$1,450.00</td>
                        </tr>
                        <tr>
                            <td style="text-align:right">IVA:</td>
                            <td style="text-align:right">$24.00</td>
                        </tr>
                        <tr>
                            <td style="text-align:right">ISR Retenido:</td>
                            <td style="text-align:right">$15.00</td>
                        </tr>
                        <tr>
                            <td style="text-align:right"><strong>Total:</strong></td>
                            <td style="text-align:right"><strong>$1,441.00</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <p><strong>Total en letra:</strong> (UN MIL CUATROCIENTOS CUARENTA Y UN PESOS 00/100 MXN)</p>

        <div class="qr-code">
            <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->generate($url)) }}" alt="QR">
        </div>

        <div class="footer">
            ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CFDI
        </div>
    </div>
</body>

</html>
