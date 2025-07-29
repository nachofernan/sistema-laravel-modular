# Ejemplo de uso - Documentos Validados en Vista Blade

## Código Blade actualizado

```php
@php
    // Obtener documentos validados desde la API
    $documentos = collect([]);
    
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('api_token'),
        ])->get(config('app.url') . '/api/proveedores/' . $proveedor->cuit . '/documentos-validados');
        
        if ($response->successful()) {
            $documentos = collect($response->json('data'));
        }
    } catch (Exception $e) {
        // Manejar error de conexión
        $documentos = collect([]);
    }
@endphp

@if ($documentos->count() == 0)
    <div class="text-gray-500 italic">No existen documentos validados asociados al proveedor</div>
@else
    @foreach ($documentos as $documento)
        <div class="bg-white shadow rounded-lg p-4 mb-2">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-gray-700">{{ $documento['tipo_documento']['nombre'] }}</span>
                <div class="flex space-x-2">
                    @if (isset($documento['vencimiento']))
                        <span class="text-sm text-gray-500">
                            Vence: {{ \Carbon\Carbon::parse($documento['vencimiento'])->format('d-m-Y') }}
                        </span>
                    @endif
                    <form action="{{ route('file.download') }}" method="POST">
                        @csrf
                        <input type="hidden" name="disk" value="proveedores">
                        <input type="hidden" name="fileName" value="{{ $documento['file_storage'] }}">
                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                            Descargar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif
```

## Ventajas de esta implementación

1. **Eficiencia**: Solo se obtienen los documentos necesarios (uno por tipo)
2. **Claridad**: La respuesta es más limpia y específica
3. **Rendimiento**: Menos datos transferidos y procesados
4. **Mantenibilidad**: Lógica centralizada en el modelo y API

## Comparación con el código anterior

### Antes (código original):
```php
@php
    $documentos = is_array($proveedor->documentos)
        ? collect($proveedor->documentos)
        : collect($proveedor->documentos ?? []);
@endphp

@foreach ($documentos as $documento)
    @php
        $doc = is_array($documento) ? (object) $documento : $documento;
        $docTipo = is_array($doc->documento_tipo ?? null)
            ? (object) $doc->documento_tipo
            : $doc->documento_tipo ?? null;
    @endphp
    // Lógica compleja para manejar múltiples documentos por tipo
@endforeach
```

### Después (nueva implementación):
```php
@php
    $documentos = collect($response->json('data'));
@endphp

@foreach ($documentos as $documento)
    // Lógica simple, un documento por tipo
@endforeach
```

## Beneficios

- **Simplicidad**: No necesitas lógica compleja para agrupar documentos por tipo
- **Consistencia**: Siempre obtienes el documento más reciente de cada tipo
- **Rendimiento**: Menos datos transferidos y procesados
- **Mantenibilidad**: La lógica de negocio está en el backend 