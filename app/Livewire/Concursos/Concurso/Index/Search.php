<?php

namespace App\Livewire\Concursos\Concurso\Index;

use App\Models\Concursos\Concurso;
use App\Models\Concursos\Estado;
use App\Models\User;
use Livewire\Component;

class Search extends Component
{
    public $search = '';
    public $gestor_id = '';
    
    // Lista de estados disponibles para filtrar (virtuales)
    public $estados_disponibles = [
        ['id' => 'precarga', 'nombre' => 'En Precarga'],
        ['id' => 'vencido', 'nombre' => 'Vencidos'],
        ['id' => 'activo', 'nombre' => 'Activos'],
        ['id' => 'cerrado', 'nombre' => 'Cerrados'],
        ['id' => 'analisis', 'nombre' => 'En Análisis'],
        ['id' => 'terminado', 'nombre' => 'Terminados'],
        ['id' => 'anulado', 'nombre' => 'Anulados'],
    ];

    public $gestores;
    public $estado_search = array();


    public function mount()
    {
        // Obtenemos los IDs de usuarios que tienen al menos un concurso
        $gestorIds = Concurso::distinct()->pluck('user_id')->toArray();
        $this->gestores = User::whereIn('id', $gestorIds)->orderBy('realname')->get();
        
        // Filtro automático si el usuario es gestor
        if(in_array(auth()->id(), $gestorIds)) {
            $this->gestor_id = auth()->id();
        }

        // Por defecto estados "vivos"
        $this->estado_search = ['precarga', 'activo', 'cerrado', 'analisis'];
    }

    public function estado_update($estado_slug)
    {
        if(in_array($estado_slug, $this->estado_search)) {
            unset($this->estado_search[array_search($estado_slug, $this->estado_search)]);
        } else {
            $this->estado_search[] = $estado_slug;
        }
        $this->estado_search = array_values($this->estado_search);
    }

    public function render()
    {
        $query = Concurso::with(['estado', 'usuario', 'invitaciones.proveedor'])
            ->orderBy('fecha_cierre', 'asc');

        if($this->search != '') {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%'.$this->search.'%')
                  ->orWhere('numero', 'like', '%'.$this->search.'%');
            });
        }

        if($this->gestor_id != '') {
            $query->where('user_id', $this->gestor_id);
        }

        // Obtenemos todos los resultados que coinciden con búsqueda y gestor
        // El filtrado por estado lo hacemos sobre la colección para contemplar estados virtuales (fechas)
        $concursos = $query->get();

        $concursosAgrupados = $concursos->groupBy(function($concurso) {
            return $concurso->estado_actual;
        })->filter(function($items, $estado) {
            return in_array($estado, $this->estado_search);
        });

        // Orden de las secciones en la vista
        $ordenSecciones = ['precarga', 'activo', 'cerrado', 'analisis', 'vencido', 'terminado', 'anulado'];
        
        return view('livewire.concursos.concurso.index.search', [
            'concursosAgrupados' => $concursosAgrupados,
            'ordenSecciones' => $ordenSecciones
        ]);
    }
}
