<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Resumen de Facturas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        .summary-info {
            margin: 30px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .facturas-list {
            margin: 30px 0;
        }

        .facturas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .facturas-table th,
        .facturas-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .facturas-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .facturas-table td {
            text-align: center;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 14px;
        }

        .totals-table th,
        .totals-table td {
            border: 2px solid #333;
            padding: 12px;
            text-align: left;
        }

        .totals-table th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .totals-table .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .totals-table .final-total {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($logo && $logo->imagen)
            <img src="{{ public_path('storage/' . $logo->imagen) }}" alt="Logo" class="logo">
        @endif
        <div class="title">RESUMEN DE FACTURAS</div>
        <div class="subtitle">Reporte generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <div class="summary-info">
        <p><strong>Cantidad de facturas incluidas:</strong> {{ $totales['cantidad'] }}</p>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($facturas->min('fecha'))->format('d/m/Y') }} al
            {{ \Carbon\Carbon::parse($facturas->max('fecha'))->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">Facturas Incluidas</div>
    <table class="facturas-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Número</th>
                <th>Proveedor</th>
                <th>Moneda</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
                @php
                    $detalle = $factura->detalles->first();
                    $moneda = $factura->moneda;
                    $totalOriginal =
                        $moneda === 'USD' && $detalle
                            ? $detalle->gravado + $detalle->iva_monto
                            : $factura->importe_total;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $factura->puntoventa }}-{{ $factura->nrofactura }}</td>
                    <td>{{ $factura->proveedor->denominacion }}</td>
                    <td>
                        {{ $factura->moneda }}
                    </td>
                    <td>
                        ${{ number_format($totalOriginal, 2) }} {{ $moneda }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $totalGravadoARS = 0;
        $totalIvaARS = 0;
        $totalGeneralARS = 0;
        foreach ($facturas as $factura) {
            $detalle = $factura->detalles->first();
            if ($factura->moneda === 'USD') {
                $totalGravadoARS += $detalle ? $detalle->gravado * $factura->tipo_cambio : 0;
                $totalIvaARS += $detalle ? $detalle->iva_monto * $factura->tipo_cambio : 0;
                $totalGeneralARS += $factura->importe_total;
            } else {
                $totalGravadoARS += $detalle ? $detalle->gravado : 0;
                $totalIvaARS += $detalle ? $detalle->iva_monto : 0;
                $totalGeneralARS += $factura->importe_total;
            }
        }
    @endphp
    <div class="section-title">Resumen de Totales</div>
    <table class="totals-table">
        <thead>
            <tr>
                <th style="width: 70%;">Concepto</th>
                <th style="width: 30%;" class="text-right">Monto ARS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Gravado (Neto)</td>
                <td class="text-right">${{ number_format($totalGravadoARS, 2) }}</td>
            </tr>
            <tr>
                <td>Total IVA</td>
                <td class="text-right">${{ number_format($totalIvaARS, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Subtotal</td>
                <td class="text-right">${{ number_format($totalGeneralARS, 2) }}</td>
            </tr>
            <tr class="final-total">
                <td><strong>TOTAL GENERAL</strong></td>
                <td class="text-right"><strong>${{ number_format($totalGeneralARS, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Este resumen incluye {{ $totales['cantidad'] }} facturas seleccionadas</p>
        <p>Los montos en ARS fueron calculados usando el tipo de cambio individual de cada factura</p>
    </div>
</body>

</html>
