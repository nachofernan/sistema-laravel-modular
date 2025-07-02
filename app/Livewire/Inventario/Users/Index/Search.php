<?php

namespace App\Livewire\Inventario\Users\Index;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{

    use WithPagination;
    
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::whereNotNull('legajo')
                    ->where(function (Builder $query) {
                        $query->where('realname', 'like', '%'.$this->search.'%')
                            ->orWhere('legajo', 'like', '%'.$this->search.'%')
                            ->orWhere('name', 'like', '%'.$this->search.'%');
                    })
                    ->paginate(20);

        return view('livewire.inventario.users.index.search', compact('users'));
    }
}
