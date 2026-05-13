<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\Concurso;
use App\Models\Usuarios\ManagedJob;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HistorialEmails extends Component
{
    public $open = false;
    public Concurso $concurso;

    public function mount(Concurso $concurso)
    {
        $this->concurso = $concurso;
    }

    public function render()
    {
        $pendientes = collect();
        $enviados = collect();

        if ($this->open) {
            $pendientes = ManagedJob::where('entity_type', 'concurso')
                ->where('entity_id', $this->concurso->id)
                ->where('status', 'pending')
                ->orderBy('scheduled_for', 'asc')
                ->get();

            $enviados = DB::table('email_logs')
                ->whereIn('emailable_type', ['concurso', Concurso::class])
                ->where('emailable_id', $this->concurso->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.concursos.concurso.show.historial-emails', [
            'pendientes' => $pendientes,
            'enviados' => $enviados,
        ]);
    }
}
