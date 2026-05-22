<?php

namespace App\Livewire\Proveedores\Externos;

use App\Models\Proveedores\ProveedorExterno;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $soloSinVincular = false;

    // Modal de reset de contraseña
    public $modalOpen = false;
    public $selectedUserId = null;
    public $selectedUsername = '';
    public $selectedEmail = '';
    public $nuevaPassword = '';
    public $confirmacionOpen = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSoloSinVincular()
    {
        $this->resetPage();
    }

    public function abrirModal($userId)
    {
        $user = ProveedorExterno::find($userId);
        if (!$user) return;

        $this->selectedUserId = $user->id;
        $this->selectedUsername = $user->username;
        $this->selectedEmail = $user->email;
        $this->nuevaPassword = '';
        $this->confirmacionOpen = false;
        $this->modalOpen = true;
    }

    public function cerrarModal()
    {
        $this->modalOpen = false;
        $this->confirmacionOpen = false;
        $this->selectedUserId = null;
        $this->nuevaPassword = '';
    }

    public function confirmar()
    {
        $this->validate([
            'nuevaPassword' => 'required|min:6',
        ], [
            'nuevaPassword.required' => 'Ingresá una contraseña provisoria.',
            'nuevaPassword.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $this->confirmacionOpen = true;
    }

    public function resetearPassword()
    {
        $user = ProveedorExterno::find($this->selectedUserId);
        if (!$user) {
            $this->cerrarModal();
            return;
        }

        $user->password = Hash::make($this->nuevaPassword);
        $user->must_change_password = true;
        $user->save();

        $this->cerrarModal();
        session()->flash('success', "Contraseña reseteada para {$user->username}.");
    }

    public function render()
    {
        $users = ProveedorExterno::with('proveedorInterno')
            ->where(function ($q) {
                $q->where('username', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Filtro cross-DB en PHP para evitar subquery entre conexiones distintas
        if ($this->soloSinVincular) {
            $users = $users->filter(fn($u) => !$u->proveedorInterno);
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 25;
        $paginatedUsers = new LengthAwarePaginator(
            $users->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $users->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.proveedores.externos.index', ['users' => $paginatedUsers]);
    }
}
