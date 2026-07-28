<?php

namespace App\Livewire\Documentos\Categorias\Show;

use App\Models\Documentos\Categoria;
use Livewire\Component;

class Edit extends Component
{
    public bool $open = false;

    public Categoria $categoria;

    public string $nombre = '';

    public bool $publica = false;

    public int $orden = 0;

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'publica' => ['boolean'],
            'orden' => ['integer', 'min:0'],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre de la categoría es obligatorio.',
    ];

    public function mount(Categoria $categoria): void
    {
        $this->categoria = $categoria;
        $this->nombre = $categoria->nombre;
        $this->publica = $categoria->publica;
        $this->orden = $categoria->orden;
    }

    public function actualizar()
    {
        // El @can de la vista no protege al componente: el chequeo va también acá.
        abort_unless(auth()->check() && auth()->user()->can('Documentos/Categorias/Editar'), 403);

        $this->validate();

        $this->categoria->update([
            'nombre' => $this->nombre,
            'publica' => $this->publica,
            'orden' => $this->orden,
        ]);

        return redirect()->route('documentos.categorias.index');
    }

    public function render()
    {
        return view('livewire.documentos.categorias.show.edit');
    }
}
