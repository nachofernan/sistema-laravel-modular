<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\Subrubro;
use Livewire\Component;

class RubrosEdit extends Component
{
    public $proveedor;
    public $search = "";

    public $open = false;

    public function mount($proveedor) {
        $this->proveedor = $proveedor;
    }

    public function agregarSubrubro($subrubroId) {
        $this->proveedor->subrubros->contains($subrubroId) ? $this->proveedor->subrubros()->detach($subrubroId) : $this->proveedor->subrubros()->attach($subrubroId);
    }

    public function marcarTodos($rubroId) {
        $rubro = Rubro::find($rubroId);
        $marcar = $rubro->subrubros->diff($this->proveedor->subrubros)->count() > 0;
        foreach($rubro->subrubros as $subrubro) {
            $marcar 
                ? $this->proveedor->subrubros()->attach($subrubro->id) 
                : $this->proveedor->subrubros()->detach($subrubro->id);
        }

        // Refresca los subrubros después de la operación
        $this->proveedor->refresh();
    }

    public function render()
    {
        if(strlen($this->search) > 2) {
            $query = $this->search;
            $rubros = Rubro::with('subrubros')
                ->where('nombre', 'LIKE', "%$query%")
                ->get();

            // Busca en los subrubros y trae su rubro asociado
            $subrubros = Subrubro::with('rubro')
                ->where('nombre', 'LIKE', "%$query%")
                ->get();

            // Combina los resultados y evita duplicados
            $resultados = collect();

            // Agrupar resultados por rubro
            $resultados = [];

            // Agregar rubros y sus subrubros
            foreach ($rubros as $rubro) {
                $resultados[$rubro->id]['rubro'] = $rubro;
                $resultados[$rubro->id]['subrubros'] = $rubro->subrubros;
            }

            // Agregar subrubros que coinciden, evitando duplicados
            foreach ($subrubros as $subrubro) {
                $rubroId = $subrubro->rubro->id;

                // Si el rubro ya existe en el array, agrega el subrubro si no está duplicado
                if (isset($resultados[$rubroId])) {
                    if (!$resultados[$rubroId]['subrubros']->contains('id', $subrubro->id)) {
                        $resultados[$rubroId]['subrubros']->push($subrubro);
                    }
                } else {
                    // Si el rubro no existe, lo agregamos con el subrubro
                    $resultados[$rubroId]['rubro'] = $subrubro->rubro;
                    $resultados[$rubroId]['subrubros'] = collect([$subrubro]);
                }
            }
        } else {
            // Combina los resultados y evita duplicados
            $resultados = collect();

            // Rubros encontrados (incluyen sus subrubros)
            foreach (Rubro::all() as $rubro) {
                $resultados->push([
                    'rubro' => $rubro,
                    'subrubros' => $rubro->subrubros,
                ]);
            }
        }
        return view('livewire.proveedores.proveedors.show.rubros-edit', compact('resultados'));
    }
}
