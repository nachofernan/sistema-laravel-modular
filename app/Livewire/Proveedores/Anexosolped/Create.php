<?php

namespace App\Livewire\Proveedores\Anexosolped;

use Livewire\Component;
use App\Models\Proveedores\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class Create extends Component
{
    // Datos del formulario
    public $titulo = '';
    public $centrales = [];
    public $documentos_adjuntos = [];
    public $visita_tecnica = '';
    public $garantia_tecnica = '';
    public $plazo_garantia = '';
    public $adjudicacion_parcial = '';
    public $marcas_alternativas = '';
    public $ingreso_planta = '';
    public $sustancias_peligrosas = '';
    
    // Contacto técnico
    public $contacto_tecnico_nombre = '';
    public $contacto_tecnico_email = '';
    public $contacto_tecnico_tel = '';
    
    // Contacto seguimiento
    public $contacto_seguimiento_nombre = '';
    public $contacto_seguimiento_email = '';
    public $contacto_seguimiento_tel = '';
    
    // Otros datos
    public $plazo_entrega = '';
    public $a_partir_de = '';
    public $lugar_entrega = '';
    public $observaciones = '';

    // Proveedores
    public $proveedores_seleccionados = [];
    public $modal_abierto = false;
    public $buscar_proveedor = '';
    public $modo_modal = 'buscar'; // 'buscar' o 'manual'
    public $proveedor_manual = [
        'razonsocial' => '',
        'cuit' => '',
        'correo' => '',
        'telefono' => ''
    ];

    // Opciones para los campos
    public $centrales_disponibles = [
        'A110 - Adm. Central',
        'A120 - Central Mar de Ajó',
        'A130 - Central Oscar Smith',
        'A140 - Central 9 de Julio',
        'A150 - Central Necochea',
        'A160 - Central Mar de Ajó II',
        'A170 - Central Brandsen',
    ];

    public $documentos_disponibles = [
        'Especificación Técnica',
        'Ficha Técnica',
        'Planos o Croquis',
        'N/A'
    ];

    protected $messages = [
        'titulo.required' => 'El título es obligatorio',
        'centrales.required' => 'Debe seleccionar al menos una central',
        'centrales.min' => 'Debe seleccionar al menos una central',
        'documentos_adjuntos.required' => 'Debe seleccionar al menos un elemento',
        'documentos_adjuntos.min' => 'Debe seleccionar al menos un elemento',
        'visita_tecnica.required' => 'Debe indicar si requiere visita técnica',
        'garantia_tecnica.required' => 'Debe indicar si requiere garantía técnica',
        'plazo_garantia.required' => 'El plazo de garantía es obligatorio cuando se requiere garantía técnica',
        'adjudicacion_parcial.required' => 'Debe indicar si admite adjudicación parcial',
        'marcas_alternativas.required' => 'Debe indicar si admite marcas alternativas',
        'ingreso_planta.required' => 'Debe indicar si requiere ingreso a planta',
        'sustancias_peligrosas.required' => 'Debe indicar si se ingresarán productos de categoría peligrosos',
        'contacto_tecnico_nombre.required' => 'El nombre del contacto técnico es obligatorio',
        'contacto_tecnico_email.required' => 'El email del contacto técnico es obligatorio',
        'contacto_tecnico_email.email' => 'El email del contacto técnico debe ser válido',
        'contacto_tecnico_tel.required' => 'El teléfono del contacto técnico es obligatorio',
        'contacto_seguimiento_nombre.required' => 'El nombre del contacto de seguimiento es obligatorio',
        'contacto_seguimiento_email.required' => 'El email del contacto de seguimiento es obligatorio',
        'contacto_seguimiento_email.email' => 'El email del contacto de seguimiento debe ser válido',
        'contacto_seguimiento_tel.required' => 'El teléfono del contacto de seguimiento es obligatorio',
        'plazo_entrega.required' => 'El plazo de entrega es obligatorio',
        'a_partir_de.required' => 'Debe indicar a partir de cuándo',
        'lugar_entrega.required' => 'El lugar de entrega es obligatorio',
        'proveedores_seleccionados.required' => 'Debe agregar al menos un proveedor',
        'proveedores_seleccionados.min' => 'Debe agregar al menos un proveedor',
        'proveedor_manual.razonsocial.required' => 'La razón social es obligatoria',
        'proveedor_manual.telefono.required' => 'El telefono es obligatorio',
        'proveedor_manual.correo.required' => 'El correo es obligatorio',
        'proveedor_manual.correo.email' => 'El correo debe ser válido',
    ];

    public function updated($propertyName)
    {
        // Limpiar plazo_garantia si garantia_tecnica es "No"
        if ($propertyName === 'garantia_tecnica' && $this->garantia_tecnica === 'No') {
            $this->plazo_garantia = '';
        }
    }

    public function abrirModal()
    {
        $this->modal_abierto = true;
        $this->modo_modal = 'buscar';
        $this->resetearFormularioModal();
    }

    public function cerrarModal()
    {
        $this->modal_abierto = false;
        $this->resetearFormularioModal();
    }

    public function cambiarModoModal($modo)
    {
        $this->modo_modal = $modo;
        $this->resetErrorBag();
    }

    private function resetearFormularioModal()
    {
        $this->buscar_proveedor = '';
        $this->proveedor_manual = [
            'razonsocial' => '',
            'cuit' => '',
            'correo' => '',
            'telefono' => ''
        ];
        $this->resetErrorBag();
    }

    public function agregarProveedorExistente($proveedorId)
    {
        $proveedor = Proveedor::find($proveedorId);
        
        if ($proveedor && !in_array($proveedorId, array_column($this->proveedores_seleccionados, 'id'))) {
            $this->proveedores_seleccionados[] = [
                'id' => $proveedor->id,
                'razonsocial' => $proveedor->razonsocial,
                'cuit' => $proveedor->cuit ?? 'N/A',
                'correo' => $proveedor->correo,
                'telefono' => $proveedor->telefono ?? 'N/A',
                'tipo' => 'existente'
            ];
            
            $this->cerrarModal();
            session()->flash('proveedor_agregado', 'Proveedor agregado correctamente');
        }
    }

    public function agregarProveedorManual()
    {
        $this->validate([
            'proveedor_manual.razonsocial' => 'required|string|max:255',
            'proveedor_manual.correo' => 'required|email',
            'proveedor_manual.cuit' => 'nullable|string',
            'proveedor_manual.telefono' => 'required|string',
        ]);

        $this->proveedores_seleccionados[] = [
            'id' => 'manual_' . uniqid(),
            'razonsocial' => $this->proveedor_manual['razonsocial'],
            'cuit' => $this->proveedor_manual['cuit'] ?: 'N/A',
            'correo' => $this->proveedor_manual['correo'],
            'telefono' => $this->proveedor_manual['telefono'],
            'tipo' => 'manual'
        ];

        $this->cerrarModal();
        session()->flash('proveedor_agregado', 'Proveedor agregado correctamente');
    }

    public function eliminarProveedor($index)
    {
        unset($this->proveedores_seleccionados[$index]);
        $this->proveedores_seleccionados = array_values($this->proveedores_seleccionados);
    }

    public function getProveedoresDisponiblesProperty()
    {
        if (empty($this->buscar_proveedor)) {
            return Proveedor::where('estado_id', '!=', 4)
                ->select('id', 'razonsocial', 'cuit', 'correo', 'telefono')
                ->orderBy('razonsocial')
                ->limit(10)
                ->get();
        }

        return Proveedor::where('estado_id', '!=', 4)
            ->where(function($query) {
                $query->where('razonsocial', 'like', '%' . $this->buscar_proveedor . '%')
                      ->orWhere('cuit', 'like', '%' . $this->buscar_proveedor . '%');
            })
            ->select('id', 'razonsocial', 'cuit', 'correo', 'telefono')
            ->orderBy('razonsocial')
            ->limit(10)
            ->get();
    }

    public function descargarPdf()
    {
        $rules = [
            'titulo' => 'required|string|max:255',
            'centrales' => 'required|array|min:1',
            'documentos_adjuntos' => 'required|array|min:1',
            'visita_tecnica' => 'required',
            'garantia_tecnica' => 'required',
            'adjudicacion_parcial' => 'required',
            'marcas_alternativas' => 'required',
            'ingreso_planta' => 'required',
            'sustancias_peligrosas' => 'required',
            'contacto_tecnico_nombre' => 'required|string|max:255',
            'contacto_tecnico_email' => 'required|email',
            'contacto_tecnico_tel' => 'required|string',
            'contacto_seguimiento_nombre' => 'required|string|max:255',
            'contacto_seguimiento_email' => 'required|email',
            'contacto_seguimiento_tel' => 'required|string',
            'plazo_entrega' => 'required|string',
            'a_partir_de' => 'required|string',
            'lugar_entrega' => 'required|string',
            'proveedores_seleccionados' => 'required|array|min:1',
        ];

        if ($this->garantia_tecnica === 'Si') {
            $rules['plazo_garantia'] = 'required|string';
        }

        $data = $this->validate($rules);
        
        // Añadimos datos que no están en las reglas de validación pero son necesarios
        $data['observaciones'] = $this->observaciones;
        $data['documentos_adjuntos'] = $this->documentos_adjuntos;

        // Generación del PDF
        $pdf = Pdf::loadView('proveedores.anexosolped.pdf', $data);
        
        // Limpiamos el nombre del archivo
        $nombreLimpio = Str::slug($this->titulo, '_'); // El segundo parámetro define el separador
        $nombreArchivo = 'Anexo_Solped_' . $nombreLimpio . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $nombreArchivo);
    }

    public function render()
    {
        return view('livewire.proveedores.anexosolped.create');
    }
}