<?php

namespace App\Livewire\Adminip\Ips\Index;

use App\Models\Adminip\IP;
use Livewire\Component;
use Livewire\WithPagination;

class TableSearch extends Component
{
    use WithPagination;

    public $search = '';

    public $bloque_a;
    public $bloque_b;
    public $bloque_c;

    public $input_a = 'todos';
    public $input_b = 'todos';
    public $input_c = 'todos';

    public function mount()
    {
        $this->bloque_a = IP::select('bloque_a')->distinct()->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $ips = IP::where('nombre', 'LIKE', '%'.$this->search.'%');
        $this->bloque_a = IP::select('bloque_a')->distinct()->get();
        if($this->input_a != 'todos') {
            $this->bloque_b = IP::select('bloque_b')->where('bloque_a', $this->input_a)->distinct()->get();
            $ips = $ips->where('bloque_a', $this->input_a);

            if($this->input_b != 'todos') {
                $this->bloque_c = IP::select('bloque_c')->where('bloque_a', $this->input_a)->where('bloque_b', $this->input_b)->distinct()->get();
                $ips = $ips->where('bloque_b', $this->input_b);

                if($this->input_c != 'todos') {
                    $ips = $ips->where('bloque_c', $this->input_c);
                }
            } else {
                $this->input_c = 'todos';
            }
        } else {
            $this->input_b = 'todos';
            $this->input_c = 'todos';
            //$ips = IP::all();
        }
        // Subconsulta para obtener las primeras 256 IPs ordenadas correctamente
        $subquery = $ips->orderByRaw('CAST(bloque_a AS SIGNED)')
        ->orderByRaw('CAST(bloque_b AS SIGNED)')
        ->orderByRaw('CAST(bloque_c AS SIGNED)')
        ->orderByRaw('CAST(bloque_d AS SIGNED)')
        ->take(256)
        ->toBase();

        // Paginar los resultados de la subconsulta
        $ips = IP::fromSub($subquery, 'sub')
        ->paginate(30);

        return view('livewire.adminip.ips.index.table-search', compact('ips'));
    }
}
