<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets 2025</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Tickets 2025 (Total: {{ $tickets->count() }})</h1>
    
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Solicitante</th>
                <th>Encargado</th>
                <th>Estado</th>
                <th>Categoría</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->codigo ?? '-' }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $ticket->user->name ?? '-' }}</td>
                    <td>{{ $ticket->encargado->name ?? '-' }}</td>
                    <td>{{ $ticket->estado->nombre ?? '-' }}</td>
                    <td>{{ $ticket->categoria->nombre ?? '-' }}</td>
                    <td>{{ $ticket->notas ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No hay tickets del 2025</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>