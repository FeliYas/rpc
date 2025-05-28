<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nuevo Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #0056b3;
        }

        .content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            border-top: 1px solid #ddd;
        }

        .total-row {
            font-weight: bold;
            text-align: right;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 3px solid #0056b3;
        }

        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .discount {
            color: #e74c3c;
        }

        .final-total {
            font-size: 16px;
            font-weight: bold;
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Nuevo Pedido Realizado</h1>
        <p>Fecha: {{ $pedido['fecha'] }}</p>
        <p>Número de Pedido: #{{ $pedido['numero_pedido'] }}</p>
    </div>

    <div class="content">
        <div class="info-section">
            <h2>Información del Cliente</h2>
            <p><strong>Nombre:</strong> {{ $pedido['cliente']->usuario }}</p>
            <p><strong>Email:</strong> {{ $pedido['cliente']->email }}</p>
            <p><strong>Teléfono:</strong> {{ $pedido['cliente']->telefono }}</p>
            <p><strong>CUIT:</strong> {{ $pedido['cliente']->cuit }}</p>
            <p><strong>Dirección:</strong> {{ $pedido['cliente']->direccion }}</p>
            <p><strong>Provincia:</strong> {{ $pedido['cliente']->provincia }}</p>
            <p><strong>Localidad:</strong> {{ $pedido['cliente']->localidad }}</p>
            <p><strong>Rol:</strong> {{ $pedido['cliente']->rol }}</p>
            <p><strong>Descuento:</strong> {{ $pedido['cliente']->descuento }}%</p>
        </div>

        <div class="info-section">
            <h2>Detalles del Pedido</h2>
            <p><strong>Método de entrega:</strong>
                @if ($pedido['entrega'] == 'retiro')
                    Retiro en fábrica
                @else
                    Transporte al interior
                @endif
            </p>

            @if (!empty($pedido['mensaje']))
                <p><strong>Mensaje del cliente:</strong></p>
                <p>{{ $pedido['mensaje'] }}</p>
            @endif

            @if (!empty($pedido['archivo']['path']))
                <p><strong>Archivo adjunto:</strong> {{ $pedido['archivo']['nombre'] ?? 'Ver adjunto en el correo' }}
                </p>
            @endif
        </div>

        <h2>Productos</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Unidad de venta</th>
                    <th>Precio unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedido['productos'] as $producto)
                    <tr>
                        <td>{{ $producto['codigo'] }}</td>
                        <td>{{ $producto['titulo'] }}</td>
                        <td>{{ $producto['unidad_venta'] }}</td>
                        <td>${{ number_format($producto['precio'], 2, ',', '.') }}</td>
                        <td>{{ $producto['cantidad'] }}</td>
                        <td>${{ number_format($producto['subtotal'], 2, ',', '.') }}</td>
                        <td class="discount">
                            @if (isset($producto['descuento_cliente_valor']) || isset($producto['descuento_aplicado_valor']))
                                -${{ number_format(($producto['descuento_cliente_valor'] ?? 0) + ($producto['descuento_aplicado_valor'] ?? 0), 2, ',', '.') }}
                            @else
                                $0,00
                            @endif
                        </td>
                        <td>${{ number_format($producto['total'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-section">
            <h3>Resumen del Pedido</h3>
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>${{ number_format($pedido['totales']['subtotal'], 2, ',', '.') }}</td>
                </tr>
                <tr class="discount">
                    <td>Descuento cliente ({{ $pedido['totales']['descuento_cliente_porcentaje'] }}%):</td>
                    <td>-${{ number_format($pedido['totales']['descuento_cliente'], 2, ',', '.') }}</td>
                </tr>
                <tr class="discount">
                    <td>Descuento productos:</td>
                    <td>-${{ number_format($pedido['totales']['descuento_productos'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Subtotal con descuentos:</td>
                    <td>${{ number_format($pedido['totales']['total_sin_iva'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>IVA (21%):</td>
                    <td>${{ number_format($pedido['totales']['iva_importe'], 2, ',', '.') }}</td>
                </tr>
                <tr class="final-total">
                    <td>TOTAL FINAL:</td>
                    <td>${{ number_format($pedido['totales']['total_con_iva'], 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no responda a este mensaje.</p>
        <p>Si tiene alguna consulta sobre su pedido, contáctenos a través de nuestros canales oficiales.</p>
        <p>© {{ date('Y') }} Todos los derechos reservados.</p>
    </div>
</body>

</html>
