<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Recibo</title>

    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #555;
        }

        .invoice-box {
            width: 220mm;
            height: 180mm;
            padding: 5px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            margin: auto;
            box-sizing: border-box;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 25px;
            line-height: 25px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 15px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
        }

        .invoice-box table tr.details td {
            padding-bottom: 15px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        h3 {
            margin: 5px 0;
            font-size: 14px;
        }

        p {
            margin: 2px 0;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                @php
                                    $path = public_path('img/fondodesp.png');
                                    $imageData = base64_encode(file_get_contents($path));
                                    $src = 'data:image/png;base64,' . $imageData;
                                @endphp
                                <img src="{{ $src }}" alt="Logo">
                            </td>
                            <td>
                                <div class="text-right">
                                    <p>COMPROBANTE DE PAGO REALIZADO POR INTERNET</p>
                                    <p>CPRI</p>
                                    <p><strong>FECHA DE EMISIÓN:</strong> {{ $receipt->payment_date }}</p>
                                    <p><strong>Identificador:</strong><br> {{ $receipt->identificator }}</p>
                                    <p><strong>TIPO DE COMPROBANTE:</strong> {{ $receipt->category->name }}</p>
                                    <p><strong>EXPEDIDO EN:</strong>{{ $receipt->counter->cp }}</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <h3 style="color:blue;">EMISOR DEL COMPROBANTE FISCAL</h3>
                                <p><strong>R.F.C. - NOMBRE O RAZÓN SOCIAL:</strong></p>
                                <p style="color:blue;">{{ $receipt->counter->rfc }} - {{ $receipt->counter->full_name }}
                                </p>
                                <p><strong>Régimen Fiscal:</strong> {{ $receipt->counter->regime->title }}</p>
                                <p><strong>Domicilio Fiscal:</strong> {{ $receipt->counter->address }}</p>
                            </td>
                            <td>
                                <h3 style="color:red;">RECEPTOR DEL COMPROBANTE FISCAL</h3>
                                <p><strong>R.F.C. - NOMBRE O RAZÓN SOCIAL:</strong></p>
                                <p style="color:red;">{{ $receipt->client->rfc }} - {{ $receipt->client->full_name }}
                                </p>
                                <p><strong>C.P:</strong> {{ $receipt->client->cp }}</p>
                                <p><strong>Régimen Fiscal:</strong> {{ $receipt->client->regime->title }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <h3>PRODUCTOS/SERVICIOS</h3>
                    <table>
                        <thead>
                            <tr class="heading">
                                <td>Código</td>
                                <td>Identificador</td>
                                <td>Producto/Servicio</td>
                                <td>Unidad</td>
                                <td>Cantidad</td>
                                <td>Precio</td>
                                <td>Importe</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item">
                                <td>{{ $receipt->id }}</td>
                                <td>{{ $receipt->identificator }}</td>
                                <td>{{ $receipt->category->description   . ' ' . $receipt->concept }}</td>
                                <td>E48</td>
                                <td>1</td>
                                <td> ${{ $receipt->mount }}</td>
                                <td>${{ $receipt->mount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <h3>DETALLES DE PAGO</h3>
                    <p><strong>Forma de Pago:</strong> {{ $receipt->pay_method }}</p>
                    <p><strong>Condiciones de Pago:</strong> {{ $receipt->status }}</p>
                    <p><strong>Moneda:</strong> MXN</p>
                    <p><strong>Cantidad:</strong> 1.00</p>
                </td>
                <td>
                    <div class="text-right">
                        <h3>TOTALES</h3>
                        <p><strong>Subtotal:</strong> ${{ $receipt->mount }}</p>
                        <p><strong>IVA 16%:</strong> $0</p>
                        <p><strong>Total:</strong> <span>${{ $receipt->mount }}</span></p>
                    </div>
                </td>
            </tr>
        </table>

        <div class="qr-code">
            <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(200)->generate($url)) }}"
                alt="QR Code">
        </div>

        <div class="footer">
            <p>ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CPRI</p>
        </div>
    </div>
</body>

</html>