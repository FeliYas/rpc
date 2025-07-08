<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->puntoventa }}-{{ $factura->nrofactura }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .totals {
            margin-top: 30px;
            text-align: right;
        }

        .total-row {
            margin: 5px 0;
        }

        .total-final {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($logo && $logo->imagen)
            <img src="{{ public_path('storage/' . $logo->imagen) }}" alt="Logo" class="logo">
        @endif
        <h1>FACTURA {{ $factura->tipo }}</h1>
    </div>

    <div class="invoice-details">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}<br>
                    <strong>Punto de Venta:</strong> {{ $factura->puntoventa }}<br>
                    <strong>Número:</strong> {{ $factura->nrofactura }}<br>
                    <strong>Moneda:</strong> {{ $factura->moneda ?? 'ARS' }}
                </td>
                <td style="width: 50%;">
                    <strong>Proveedor:</strong> {{ $factura->proveedor->denominacion }}<br>
                    <strong>DNI/CUIT:</strong> {{ $factura->proveedor->dni }}<br>
                    @if ($factura->moneda === 'USD')
                        <strong>Tipo de Cambio:</strong> ${{ number_format($factura->tipo_cambio, 4) }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if ($factura->moneda === 'USD')
        <table class="details-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto (USD)</th>
                    <th>IVA %</th>
                    <th>IVA Monto (USD)</th>
                    <th>Subtotal (USD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura->detalles as $detalle)
                    <tr>
                        <td>Servicios Gravados</td>
                        <td>${{ number_format($detalle->gravado, 2) }}</td>
                        <td>{{ $detalle->iva_porcentaje }}%</td>
                        <td>${{ number_format($detalle->iva_monto, 2) }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="totals">
            @php
                $totalUSD = $factura->detalles->sum('subtotal');
            @endphp
            <div class="total-row">
                <strong>Total en USD: ${{ number_format($totalUSD, 2) }}</strong>
            </div>
            <div class="total-row total-final">
                <strong>TOTAL EN ARS: ${{ number_format($factura->importe_total, 2) }}</strong>
            </div>
        </div>
    @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto (ARS)</th>
                    <th>IVA %</th>
                    <th>IVA Monto (ARS)</th>
                    <th>Subtotal (ARS)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura->detalles as $detalle)
                    <tr>
                        <td>Servicios Gravados</td>
                        <td>${{ number_format($detalle->gravado, 2) }}</td>
                        <td>{{ $detalle->iva_porcentaje }}%</td>
                        <td>${{ number_format($detalle->iva_monto, 2) }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="totals">
            @php
                $totalARS = $factura->detalles->sum('subtotal');
            @endphp
            <div class="total-row total-final">
                <strong>TOTAL EN ARS: ${{ number_format($factura->importe_total, 2) }}</strong>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Factura generada el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>
