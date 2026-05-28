<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Maquina;
use App\Models\Despacho\Registrador;
use Livewire\Component;

class MaquinasIndex extends Component
{
    // ── Estado ────────────────────────────────────────────────
    public ?int $expandedMaquinaId = null;

    // ── Formulario máquina ────────────────────────────────────
    public bool   $showMaquinaForm     = false;
    public ?int   $editingMaquinaId    = null;
    public string $maquina_codigo      = '';
    public string $maquina_nombre      = '';
    public string $maquina_descripcion = '';
    public bool   $maquina_activa      = true;

    // ── Asignación ────────────────────────────────────────────
    public ?int $asignar_registrador_id = null;

    // ── Listeners ─────────────────────────────────────────────
    protected $listeners = ['registradorGuardado' => '$refresh'];

    // ── Validación ────────────────────────────────────────────
    protected function maquinaRules(): array
    {
        $unique = 'unique:despacho.maquinas,codigo';
        if ($this->editingMaquinaId) {
            $unique .= ',' . $this->editingMaquinaId;
        }
        return [
            'maquina_codigo'      => ['required', 'string', 'max:20', $unique],
            'maquina_nombre'      => ['required', 'string', 'max:100'],
            'maquina_descripcion' => ['nullable', 'string'],
            'maquina_activa'      => ['boolean'],
        ];
    }

    // ── Acciones máquina ──────────────────────────────────────
    public function nuevaMaquina(): void
    {
        $this->resetMaquinaForm();
        $this->showMaquinaForm  = true;
        $this->editingMaquinaId = null;
    }

    public function editarMaquina(int $id): void
    {
        $m = Maquina::findOrFail($id);
        $this->editingMaquinaId    = $id;
        $this->maquina_codigo      = $m->codigo;
        $this->maquina_nombre      = $m->nombre;
        $this->maquina_descripcion = $m->descripcion ?? '';
        $this->maquina_activa      = $m->activa;
        $this->showMaquinaForm     = true;
    }

    public function guardarMaquina(): void
    {
        $this->validate($this->maquinaRules());

        $payload = [
            'codigo'      => strtoupper($this->maquina_codigo),
            'nombre'      => $this->maquina_nombre,
            'descripcion' => $this->maquina_descripcion ?: null,
            'activa'      => $this->maquina_activa,
        ];

        if ($this->editingMaquinaId) {
            Maquina::findOrFail($this->editingMaquinaId)->update($payload);
        } else {
            Maquina::create($payload);
        }

        $this->resetMaquinaForm();
        $this->showMaquinaForm = false;
    }

    public function cancelarMaquina(): void
    {
        $this->resetMaquinaForm();
        $this->showMaquinaForm = false;
    }

    public function toggleMaquina(int $id): void
    {
        $m = Maquina::findOrFail($id);
        $m->update(['activa' => !$m->activa]);
    }

    public function expandirMaquina(int $id): void
    {
        $this->expandedMaquinaId      = ($this->expandedMaquinaId === $id) ? null : $id;
        $this->asignar_registrador_id = null;
    }

    protected function resetMaquinaForm(): void
    {
        $this->editingMaquinaId    = null;
        $this->maquina_codigo      = '';
        $this->maquina_nombre      = '';
        $this->maquina_descripcion = '';
        $this->maquina_activa      = true;
        $this->resetErrorBag();
    }

    // ── Asignaciones ──────────────────────────────────────────
    public function asignarRegistrador(int $maquinaId): void
    {
        $this->validate([
            'asignar_registrador_id' => ['required', 'exists:despacho.registradores,id'],
        ]);

        Maquina::findOrFail($maquinaId)
            ->registradores()
            ->syncWithoutDetaching([$this->asignar_registrador_id]);

        $this->asignar_registrador_id = null;
        $this->dispatch('asignacionCambiada');
    }

    public function desasignarRegistrador(int $maquinaId, int $registradorId): void
    {
        Maquina::findOrFail($maquinaId)->registradores()->detach($registradorId);
        $this->dispatch('asignacionCambiada');
    }

    // ── Render ────────────────────────────────────────────────
    public function render()
    {
        $maquinas = Maquina::withCount('registradores')
            ->orderBy('codigo')
            ->get();

        $registradoresAsignados   = collect();
        $registradoresDisponibles = collect();

        if ($this->expandedMaquinaId) {
            $maquina = Maquina::find($this->expandedMaquinaId);

            $registradoresAsignados = $maquina->registradores()
                ->orderByRaw("CASE tipo
                        WHEN 'principal' THEN 1
                        WHEN 'respaldo'  THEN 2
                        WHEN 'control'   THEN 3
                        WHEN 'auxiliar'  THEN 4
                        ELSE 5
                    END")
                ->orderBy('codigo')
                ->get();

            $registradoresDisponibles = Registrador::where('activo', true)
                ->whereNotIn('id', $registradoresAsignados->pluck('id'))
                ->orderBy('codigo')
                ->get();
        }

        return view('livewire.despacho.maquinas-index', compact(
            'maquinas',
            'registradoresAsignados',
            'registradoresDisponibles',
        ));
    }
}