<?php

namespace App\Livewire\Proveedores\Proveedors\Index;

use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\Subrubro;
use Livewire\Component;

class RubroSearchModal extends Component
{
    public $search = "";
    public $open = false;

    public function mount() {}

    public function selectRubro($rubroId)
    {
        $this->dispatch('rubro-selected', rubroId: $rubroId);
        $this->open = false;
    }

    public function selectSubrubro($rubroId, $subrubroId)
    {
        $this->dispatch('rubro-selected', rubroId: $rubroId, subrubroId: $subrubroId);
        $this->open = false;
        $this->search = ""; // Limpiar búsqueda para próxima vez
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
            // Si no hay búsqueda, mostrar todos
            $resultados = [];
            foreach (Rubro::with('subrubros')->get() as $rubro) {
                $resultados[$rubro->id] = [
                    'rubro' => $rubro,
                    'subrubros' => $rubro->subrubros,
                ];
            }
        }

        return view('livewire.proveedores.proveedors.index.rubro-search-modal', compact('resultados'));
    }
}