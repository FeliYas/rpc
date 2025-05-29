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
        <p><strong>Tipo de cambio promedio:</strong> ${{ number_format($totales['promedio_tipo_cambio'], 4) }}</p>
    </div>

    <div class="section-title">Facturas Incluidas</div>
    <table class="facturas-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Número</th>
                <th>Proveedor</th>
                <th>Tipo Cambio</th>
                <th>Total ARS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $factura->tipo }}</td>
                    <td>{{ $factura->puntoventa }}-{{ $factura->nrofactura }}</td>
                    <td>{{ $factura->proveedor->denominacion }}</td>
                    <td>${{ number_format($factura->tipo_cambio, 4) }}</td>
                    <td>${{ number_format($factura->importe_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Resumen de Totales</div>
    <table class="totals-table">
        <thead>
            <tr>
                <th style="width: 50%;">Concepto</th>
                <th style="width: 25%;" class="text-right">Monto USD</th>
                <th style="width: 25%;" class="text-right">Monto ARS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Gravado (Neto)</td>
                <td class="text-right">${{ number_format($totales['gravado_usd'], 2) }}</td>
                <td class="text-right">${{ number_format($totales['gravado_ars'], 2) }}</td>
            </tr>
            <tr>
                <td>Total IVA</td>
                <td class="text-right">${{ number_format($totales['iva_usd'], 2) }}</td>
                <td class="text-right">${{ number_format($totales['iva_ars'], 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Subtotal</td>
                <td class="text-right">${{ number_format($totales['general_usd'], 2) }}</td>
                <td class="text-right">${{ number_format($totales['general_ars'], 2) }}</td>
            </tr>
            <tr class="final-total">
                <td><strong>TOTAL GENERAL</strong></td>
                <td class="text-right"><strong>${{ number_format($totales['general_usd'], 2) }}</strong></td>
                <td class="text-right"><strong>${{ number_format($totales['general_ars'], 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Este resumen incluye {{ $totales['cantidad'] }} facturas seleccionadas</p>
        <p>Los montos en ARS fueron calculados usando el tipo de cambio individual de cada factura</p>
    </div>
</body>

</html>
