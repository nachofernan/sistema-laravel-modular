<?php

namespace App\Livewire\Usuarios\Areas;

use App\Models\User;
use App\Models\Usuarios\Area;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Miembros extends Component
{
    public Area $area;
    public bool $showModal = false;
    public string $search = '';

    public function mount(Area $area)
    {
        $this->authorizeEdit();
        $this->area = $area;
    }

    #[Computed]
    public function miembros()
    {
        return User::with('cargo')
            ->where('area_id', $this->area->id)
            ->orderBy('realname')
            ->get();
    }

    #[Computed]
    public function disponibles()
    {
        return User::with('area')
            ->where(function ($q) {
                $q->where('area_id', '!=', $this->area->id)->orWhereNull('area_id');
            })
            ->when($this->search !== '', function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('realname', 'like', $term)
                        ->orWhere('name', 'like', $term)
                        ->orWhere('nombre', 'like', $term)
                        ->orWhere('apellido', 'like', $term)
                        ->orWhere('legajo', 'like', $term);
                });
            })
            ->orderBy('realname')
            ->limit(25)
            ->get();
    }

    public function agregar(int $userId)
    {
        $this->authorizeEdit();

        $user = User::find($userId);
        if ($user) {
            $user->update(['area_id' => $this->area->id]);
        }

        unset($this->miembros, $this->disponibles);
    }

    public function quitar(int $userId)
    {
        $this->authorizeEdit();

        $user = User::where('area_id', $this->area->id)->find($userId);
        if ($user) {
            $user->update(['area_id' => null]);

            // Si el que sale era el responsable, el área queda sin responsable.
            if ($this->area->responsable_id == $user->id) {
                $this->area->update(['responsable_id' => null]);
            }
        }

        unset($this->miembros, $this->disponibles);
    }

    public function marcarResponsable(int $userId)
    {
        $this->authorizeEdit();

        // Solo un miembro del área puede ser responsable.
        $esMiembro = User::where('area_id', $this->area->id)->whereKey($userId)->exists();
        if ($esMiembro) {
            $this->area->update(['responsable_id' => $userId]);
        }
    }

    public function quitarResponsable()
    {
        $this->authorizeEdit();

        $this->area->update(['responsable_id' => null]);
    }

    protected function authorizeEdit(): void
    {
        abort_unless(auth()->check() && auth()->user()->can('Usuarios/Areas/Editar'), 403);
    }

    public function render()
    {
        return view('livewire.usuarios.areas.miembros');
    }
}
