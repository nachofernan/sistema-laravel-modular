<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Maquina;
use App\Models\Despacho\Registrador;
use Livewire\Component;

class RegistradoresIndex extends Component
{
    // ── Estado ────────────────────────────────────────────────
    public ?int $expandedRegistradorId = null;

    // ── Formulario ────────────────────────────────────────────
    public bool   $showForm          = false;
    public ?int   $editingId         = null;
    public string $reg_codigo        = '';
    public string $reg_nombre        = '';
    public string $reg_tipo          = 'principal'; // principal | respaldo | control | auxiliar
    public string $reg_tipo_dato     = 'pulsos';
    public int    $reg_columna_datos = 2;
    public string $reg_factor        = '1.000000';
    public string $reg_notas         = '';
    public bool   $reg_activo        = true;
    public ?int   $reg_maquina_id    = null; // asignación inicial al crear (opcional)

    // ── Asignación desde este lado ────────────────────────────
    public ?int $asignar_maquina_id = null;

    // ── Listeners ─────────────────────────────────────────────
    protected $listeners = ['asignacionCambiada' => '$refresh'];

    // ── Validación ────────────────────────────────────────────
    protected function rules(): array
    {
        $unique = 'unique:despacho.registradores,codigo';
        if ($this->editingId) {
            $unique .= ',' . $this->editingId;
        }
        return [
            'reg_codigo'        => ['required', 'string', 'max:30', $unique],
            'reg_nombre'        => ['nullable', 'string', 'max:100'],
            'reg_tipo'          => ['required', 'in:principal,respaldo,control,auxiliar'],
            'reg_tipo_dato'     => ['required', 'in:pulsos,potencia'],
            'reg_columna_datos' => ['required', 'integer', 'min:1', 'max:20'],
            'reg_factor'        => ['required', 'numeric', 'min:0.000001'],
            'reg_notas'         => ['nullable', 'string'],
            'reg_activo'        => ['boolean'],
            'reg_maquina_id'    => ['nullable', 'exists:despacho.maquinas,id'],
        ];
    }

    // ── Acciones ──────────────────────────────────────────────
    public function nuevo(): void
    {
        $this->resetForm();
        $this->showForm  = true;
        $this->editingId = null;
    }

    public function editar(int $id): void
    {
        $reg = Registrador::findOrFail($id);
        $this->editingId         = $id;
        $this->reg_codigo        = $reg->codigo;
        $this->reg_nombre        = $reg->nombre ?? '';
        $this->reg_tipo          = $reg->tipo;
        $this->reg_tipo_dato     = $reg->tipo_dato;
        $this->reg_columna_datos = $reg->columna_datos;
        $this->reg_factor        = $reg->factor_conversion;
        $this->reg_notas         = $reg->notas ?? '';
        $this->reg_activo        = $reg->activo;
        $this->reg_maquina_id    = null;
        $this->showForm          = true;
        $this->expandedRegistradorId = null;
    }

    public function guardar(): void
    {
        $this->validate($this->rules());

        $payload = [
            'codigo'            => strtoupper($this->reg_codigo),
            'nombre'            => $this->reg_nombre ?: null,
            'tipo'              => $this->reg_tipo,
            'tipo_dato'         => $this->reg_tipo_dato,
            'columna_datos'     => $this->reg_columna_datos,
            'factor_conversion' => $this->reg_factor,
            'notas'             => $this->reg_notas ?: null,
            'activo'            => $this->reg_activo,
        ];

        if ($this->editingId) {
            Registrador::findOrFail($this->editingId)->update($payload);
        } else {
            $reg = Registrador::create($payload);
            if ($this->reg_maquina_id) {
                $reg->maquinas()->attach($this->reg_maquina_id);
            }
        }

        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('registradorGuardado');
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function toggle(int $id): void
    {
        $reg = Registrador::findOrFail($id);
        $reg->update(['activo' => !$reg->activo]);
    }

    public function expandir(int $id): void
    {
        $this->expandedRegistradorId = ($this->expandedRegistradorId === $id) ? null : $id;
        $this->asignar_maquina_id    = null;
    }

    protected function resetForm(): void
    {
        $this->editingId         = null;
        $this->reg_codigo        = '';
        $this->reg_nombre        = '';
        $this->reg_tipo          = 'principal';
        $this->reg_tipo_dato     = 'pulsos';
        $this->reg_columna_datos = 2;
        $this->reg_factor        = '1.000000';
        $this->reg_notas         = '';
        $this->reg_activo        = true;
        $this->reg_maquina_id    = null;
        $this->resetErrorBag();
    }

    // ── Asignaciones ──────────────────────────────────────────
    public function asignarMaquina(int $registradorId): void
    {
        $this->validate([
            'asignar_maquina_id' => ['required', 'exists:despacho.maquinas,id'],
        ]);

        Registrador::findOrFail($registradorId)
            ->maquinas()
            ->syncWithoutDetaching([$this->asignar_maquina_id]);

        $this->asignar_maquina_id = null;
        $this->dispatch('registradorGuardado');
    }

    public function desasignarMaquina(int $registradorId, int $maquinaId): void
    {
        Registrador::findOrFail($registradorId)->maquinas()->detach($maquinaId);
        $this->dispatch('registradorGuardado');
    }

    // ── Render ────────────────────────────────────────────────
    public function render()
    {
        $registradores = Registrador::withCount('maquinas')
            ->orderBy('codigo')
            ->get();

        $maquinasAsignadas   = collect();
        $maquinasDisponibles = collect();
        $todasLasMaquinas    = Maquina::where('activa', true)->orderBy('codigo')->get();

        if ($this->expandedRegistradorId) {
            $reg = Registrador::find($this->expandedRegistradorId);

            $maquinasAsignadas = $reg->maquinas()->orderBy('codigo')->get();

            $maquinasDisponibles = Maquina::where('activa', true)
                ->whereNotIn('id', $maquinasAsignadas->pluck('id'))
                ->orderBy('codigo')
                ->get();
        }

        return view('livewire.despacho.registradores-index', compact(
            'registradores',
            'maquinasAsignadas',
            'maquinasDisponibles',
            'todasLasMaquinas',
        ));
    }
}