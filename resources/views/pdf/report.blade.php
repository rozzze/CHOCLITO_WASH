<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1a202c;
        }
        .header p {
            margin: 5px 0;
            color: #718096;
        }
        .filters-summary {
            background-color: #f7fafc;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .summary-cards {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-cards td {
            text-align: center;
            padding: 10px;
            background-color: #edf2f7;
            border: 1px solid #fff;
            width: 33%;
        }
        .summary-label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: #718096;
        }
        .summary-value {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f7fafc;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #4a5568;
        }
        td {
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .status-completado { background-color: #c6f6d5; color: #22543d; }
        .status-cancelado { background-color: #fed7d7; color: #822727; }
        .status-pendiente { background-color: #ffebee; color: #744210; }
        .status-default { background-color: #ebf8ff; color: #2c5282; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Pedidos - Choclito Wash</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }} | Usuario: {{ auth()->user()->name }}</p>
    </div>

    <!-- Resumen de Filtros Aplicados -->
    <div class="filters-summary">
        <strong>Filtros aplicados:</strong><br>
        Fecha: {{ \Carbon\Carbon::parse($filters['dateFrom'])->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($filters['dateTo'])->format('d/m/Y') }}
        @if($filters['status']) | Estado: {{ ucfirst(str_replace('_', ' ', $filters['status'])) }} @endif
        @if($filters['clientId']) | Cliente ID: {{ $filters['clientId'] }} @endif
        @if($filters['repartidorId']) | Repartidor ID: {{ $filters['repartidorId'] }} @endif
    </div>

    <!-- Totales -->
    <table class="summary-cards">
        <tr>
            <td>
                <span class="summary-label">Total Pedidos</span>
                <span class="summary-value">{{ number_format($summary['total_orders']) }}</span>
            </td>
            <td>
                <span class="summary-label">Ingresos</span>
                <span class="summary-value">${{ number_format($summary['total_revenue'], 2) }}</span>
            </td>
            <td>
                <span class="summary-label">Ticket Promedio</span>
                <span class="summary-value">${{ number_format($summary['avg_ticket'], 2) }}</span>
            </td>
        </tr>
    </table>

    <!-- Listado -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>
                        @php
                            $statusClass = 'status-default';
                            if ($order->status === 'completado') $statusClass = 'status-completado';
                            elseif ($order->status === 'cancelado') $statusClass = 'status-cancelado';
                            elseif ($order->status === 'pendiente') $statusClass = 'status-pendiente';
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: center; color: #a0aec0; margin-top: 30px;">
        Fin del Reporte
    </p>
</body>
</html>
