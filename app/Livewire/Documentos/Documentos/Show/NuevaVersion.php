<?php

namespace App\Livewire\Documentos\Documentos\Show;

use App\Models\Documentos\Documento;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Atajo para reemplazar el archivo de un documento sin pasar por la edición
 * completa. Sólo toca lo que cambia junto con el archivo: el número de versión,
 * el código de Control de Gestión (que trae su propio `_vN`) y la nota del
 * cambio. El resto de los datos se editan desde el formulario de edición.
 */
class NuevaVersion extends Component
{
    use WithFileUploads;

    public bool $open = false;

    public Documento $documento;

    public $archivo;

    /** Sugerida: la siguiente. Se puede pisar si el documento trae otra numeración. */
    public int $version = 1;

    public ?string $codigo = null;

    public string $notas = '';

    protected function rules(): array
    {
        return [
            'archivo' => [
                'required',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,txt,jpg,jpeg,png,mp4',
            ],
            // La versión actual pasa al historial, así que la nueva tiene que superarla:
            // repetir un número rompería el índice único de `documento_versiones`.
            'version' => ['required', 'integer', 'min:'.($this->documento->version + 1)],
            'codigo' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function messages(): array
    {
        return [
            'archivo.required' => 'Hay que adjuntar el archivo de la nueva versión.',
            'archivo.max' => 'El archivo no puede superar los 50 MB.',
            'archivo.mimes' => 'El tipo de archivo no está permitido.',
            'version.required' => 'Hay que indicar el número de la versión nueva.',
            'version.min' => "La versión nueva tiene que ser mayor que la actual (v{$this->documento->version}).",
            'notas.max' => 'La nota del cambio no puede superar los 255 caracteres.',
        ];
    }

    public function mount(Documento $documento): void
    {
        $this->documento = $documento;
        $this->version = $documento->version + 1;
        $this->codigo = $documento->codigo;
    }

    public function guardar()
    {
        // El @can de la vista no protege al componente: el chequeo va también acá.
        abort_unless(auth()->check() && auth()->user()->can('Documentos/Documentos/Editar'), 403);

        $this->validate();

        $this->documento->reemplazarArchivo(
            $this->archivo,
            $this->notas !== '' ? $this->notas : null,
            auth()->id(),
            $this->version,
        );

        $this->documento->codigo = $this->codigo ?: null;
        $this->documento->save();

        return redirect()->route('documentos.documentos.show', $this->documento);
    }

    public function render()
    {
        return view('livewire.documentos.documentos.show.nueva-version');
    }
}
