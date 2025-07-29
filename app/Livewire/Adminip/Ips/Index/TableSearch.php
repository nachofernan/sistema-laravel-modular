<?php

namespace App\Livewire\Adminip\Ips\Index;

use App\Models\Adminip\IP;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

class TableSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $bloque_a = 'todos';
    public $bloque_b = 'todos';
    public $bloque_c = 'todos';
    public $selectedIp = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showCheckModal = false;
    public $checkingIp = '';
    public $checkResult = null;
    public $isChecking = false;

    // Cache para los bloques
    public $bloques_a = [];
    public $bloques_b = [];
    public $bloques_c = [];

    protected $listeners = ['closeModals' => 'closeModals', 'refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->loadBloques();
    }

    public function loadBloques()
    {
        $this->bloques_a = IP::select('bloque_a')
            ->distinct()
            ->orderBy('bloque_a')
            ->pluck('bloque_a')
            ->toArray();

        if ($this->bloque_a !== 'todos') {
            $this->bloques_b = IP::select('bloque_b')
                ->where('bloque_a', $this->bloque_a)
                ->distinct()
                ->orderBy('bloque_b')
                ->pluck('bloque_b')
                ->toArray();

            if ($this->bloque_b !== 'todos') {
                $this->bloques_c = IP::select('bloque_c')
                    ->where('bloque_a', $this->bloque_a)
                    ->where('bloque_b', $this->bloque_b)
                    ->distinct()
                    ->orderBy('bloque_c')
                    ->pluck('bloque_c')
                    ->toArray();
            } else {
                $this->bloques_c = [];
            }
        } else {
            $this->bloques_b = [];
            $this->bloques_c = [];
        }
    }

    public function updatedBloque_a()
    {
        $this->bloque_b = 'todos';
        $this->bloque_c = 'todos';
        $this->loadBloques();
        $this->resetPage();
    }

    public function updatedBloque_b()
    {
        $this->bloque_c = 'todos';
        $this->loadBloques();
        $this->resetPage();
    }

    public function updatedBloque_c()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editIp($ipId)
    {
        $this->selectedIp = IP::find($ipId);
        $this->showEditModal = true;
    }

    public function deleteIp($ipId)
    {
        $this->selectedIp = IP::find($ipId);
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->selectedIp) {
            $this->selectedIp->delete();
            $this->showDeleteModal = false;
            $this->selectedIp = null;
            session()->flash('message', 'IP eliminada correctamente.');
        }
    }

    public function closeModals()
    {
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showCheckModal = false;
        $this->selectedIp = null;
        $this->checkingIp = '';
        $this->checkResult = null;
        $this->isChecking = false;
    }

    public function checkIpStatus($ip)
    {
        $this->checkingIp = $ip;
        $this->showCheckModal = true;
        $this->isChecking = true;
        $this->checkResult = null;

        try {
            $response = Http::timeout(10)->get('http://172.17.9.231/checkip/index.php?ip=' . $ip);
            $isActive = $response['activo'] ?? false;
            
            if ($isActive) {
                $this->checkResult = [
                    'status' => 'success',
                    'message' => "La IP {$ip} está activa y responde correctamente."
                ];
            } else {
                $this->checkResult = [
                    'status' => 'error',
                    'message' => "La IP {$ip} no responde o está inactiva."
                ];
            }
        } catch (\Exception $e) {
            $this->checkResult = [
                'status' => 'error',
                'message' => "Error al verificar la IP {$ip}: " . $e->getMessage()
            ];
        }

        $this->isChecking = false;
    }

    public function render()
    {
        // Cargar bloques en cada render para asegurar que estén actualizados
        $this->loadBloques();
        
        $query = IP::query();

        // Filtros
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nombre', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('ip', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'LIKE', '%' . $this->search . '%');
            });
        }

        if ($this->bloque_a !== 'todos') {
            $query->where('bloque_a', $this->bloque_a);
            
            if ($this->bloque_b !== 'todos') {
                $query->where('bloque_b', $this->bloque_b);
                
                if ($this->bloque_c !== 'todos') {
                    $query->where('bloque_c', $this->bloque_c);
                }
            }
        }

        // Ordenamiento optimizado
        $ips = $query->orderByRaw('CAST(bloque_a AS SIGNED)')
            ->orderByRaw('CAST(bloque_b AS SIGNED)')
            ->orderByRaw('CAST(bloque_c AS SIGNED)')
            ->orderByRaw('CAST(bloque_d AS SIGNED)')
            ->paginate(50);

        return view('livewire.adminip.ips.index.table-search', compact('ips'));
    }
}
