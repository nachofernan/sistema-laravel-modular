<?php

namespace App\Livewire\Dashboard;

use App\Models\Tickets\Categoria;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\Documento;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NuevoTicket extends Component
{
    use WithFileUploads;
    
    public $categoria_id;
    public $notas;
    public $documento;
    public $categorias;
    public $enviando = false;
    
    protected $rules = [
        'categoria_id' => 'required',
        'notas' => 'required|min:10|max:1000',
        'documento' => 'nullable|file|max:10240' // 10MB max
    ];
    
    protected $messages = [
        'categoria_id.required' => 'Debe seleccionar una categoría',
        'categoria_id.exists' => 'La categoría seleccionada no es válida',
        'notas.required' => 'Debe describir su consulta o problema',
        'notas.min' => 'La descripción debe tener al menos 10 caracteres',
        'notas.max' => 'La descripción no puede exceder 1000 caracteres',
        'documento.max' => 'El archivo no puede exceder 10MB'
    ];
    
    public function mount()
    {
        $this->categorias = Categoria::all();
    }
    
    public function updatedCategoriaId()
    {
        // Validar si la categoría requiere documento obligatorio
        if ($this->categoria_id == 9) { // Asumiendo que 9 es la categoría que requiere documento
            $this->rules['documento'] = 'required|file|max:10240';
            $this->messages['documento.required'] = 'Esta categoría requiere adjuntar un documento';
        } else {
            $this->rules['documento'] = 'nullable|file|max:10240';
            unset($this->messages['documento.required']);
        }
    }
    
    public function crearTicket()
    {
        $this->enviando = true;
        
        try {
            $this->validate();
            
            // Crear el ticket
            $ticket = Ticket::create([
                'codigo' => null,
                'user_id' => Auth::id(),
                'categoria_id' => $this->categoria_id,
                'notas' => $this->notas,
                'estado_id' => 1, // Estado inicial (abierto)
            ]);
            $ticket->codigo = str_pad($ticket->id, 6, '0', STR_PAD_LEFT);
            $ticket->save();
            
            // Subir documento si existe usando Spatie Media Library
            if ($this->documento) {
                $documento = Documento::create([
                    'archivo' => $ticket->codigo . '_' . $this->documento->getClientOriginalName(),
                    'file_storage' => $this->documento->getClientOriginalName(),
                    'ticket_id' => $ticket->id,
                ]);
                
                $documento->addMedia($this->documento->getRealPath())
                    ->usingName($this->documento->getClientOriginalName())
                    ->usingFileName($ticket->codigo . '_' . $this->documento->getClientOriginalName())
                    ->toMediaCollection('archivos', 'tickets');
            }
            
            // Limpiar formulario
            $this->reset(['categoria_id', 'notas', 'documento']);
            
            // Notificar otros componentes
            $this->dispatch('ticketCreado');
            $this->dispatch('ticketActualizado');
            
            session()->flash('mensaje_verde', 'Ticket #' . $ticket->codigo . ' creado exitosamente');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->enviando = false;
            throw $e;
        } 
        
        $this->enviando = false;
    }
    
    public function render()
    {
        return view('livewire.dashboard.nuevo-ticket');
    }
}