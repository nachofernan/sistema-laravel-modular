<?php

namespace App\Livewire\Usuarios\Users\Show;

use App\Models\User;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\Cargo;
use App\Models\Usuarios\Sede;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

/**
 * Historial de cambios del usuario (activity_log) dentro de su ficha.
 * Lo gobierna el mismo permiso que ver la ficha: no lleva permiso propio.
 *
 * Cubierto por: la_ficha_muestra_el_historial_con_valor_anterior_y_nuevo,
 * un_usuario_sin_permiso_no_ve_el_historial.
 */
class Historial extends Component
{
    use WithPagination;

    public User $user;

    /** Etiquetas legibles de los campos que registra User::getActivitylogOptions(). */
    public const CAMPOS = [
        'name' => 'Usuario',
        'realname' => 'Nombre real',
        'nombre' => 'Nombre',
        'apellido' => 'Apellido',
        'email' => 'Email',
        'legajo' => 'Legajo',
        'interno' => 'Interno',
        'visible' => 'Visible',
        'area_id' => 'Área',
        'sede_id' => 'Sede',
        'cargo_id' => 'Cargo',
    ];

    public function render()
    {
        $this->authorize('Usuarios/Usuarios/Ver');

        $actividades = Activity::where('subject_type', User::class)
            ->where('subject_id', $this->user->id)
            ->with('causer')
            ->latest('id')
            ->paginate(10, pageName: 'historial');

        return view('livewire.usuarios.users.show.historial', [
            'actividades' => $actividades,
            'nombres' => $this->nombresDeReferencias($actividades->getCollection()),
        ]);
    }

    /**
     * Traduce un valor guardado a algo legible (ids de área/sede/cargo → nombre, visible → Sí/No).
     */
    public function valorLegible(string $campo, mixed $valor, array $nombres): string
    {
        if ($valor === null || $valor === '') {
            return '—';
        }

        return match ($campo) {
            'visible' => $valor ? 'Sí' : 'No',
            'area_id', 'sede_id', 'cargo_id' => $nombres[$campo][$valor] ?? "#{$valor} (eliminado)",
            default => (string) $valor,
        };
    }

    /**
     * Una consulta por catálogo con todos los ids que aparecen en la página.
     *
     * @return array<string, array<int, string>>
     */
    private function nombresDeReferencias(Collection $actividades): array
    {
        $ids = ['area_id' => [], 'sede_id' => [], 'cargo_id' => []];

        foreach ($actividades as $actividad) {
            foreach (['old', 'attributes'] as $lado) {
                foreach (array_keys($ids) as $campo) {
                    $valor = $actividad->properties[$lado][$campo] ?? null;
                    if ($valor !== null) {
                        $ids[$campo][] = $valor;
                    }
                }
            }
        }

        return [
            'area_id' => Area::whereIn('id', $ids['area_id'])->pluck('nombre', 'id')->all(),
            'sede_id' => Sede::whereIn('id', $ids['sede_id'])->pluck('nombre', 'id')->all(),
            'cargo_id' => Cargo::whereIn('id', $ids['cargo_id'])->pluck('nombre', 'id')->all(),
        ];
    }
}
