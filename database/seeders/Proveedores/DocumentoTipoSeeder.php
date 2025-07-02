<?php

namespace Database\Seeders\Proveedores;

use App\Models\Proveedores\DocumentoTipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocumentoTipo::create([ 'id' => '1', 'nombre' => 'DDJJ DATOS/ DOMICILIO', 'codigo' => '002', 'vencimiento' => '1', ]);
        DocumentoTipo::create([ 'id' => '2', 'nombre' => 'DDJJ INTERESES', 'codigo' => '003', 'vencimiento' => '1', ]);
        DocumentoTipo::create([ 'id' => '3', 'nombre' => 'PLIEGO GENERAL', 'codigo' => '006', 'vencimiento' => '0', ]);
        DocumentoTipo::create([ 'id' => '4', 'nombre' => 'CONSTANCIAS IMPOSITIVAS', 'codigo' => '001', 'vencimiento' => '1', ]);
        DocumentoTipo::create([ 'id' => '5', 'nombre' => 'ESTATUTO', 'codigo' => '004', 'vencimiento' => '1', ]);
        DocumentoTipo::create([ 'id' => '6', 'nombre' => 'BALANCE', 'codigo' => '005', 'vencimiento' => '0', ]);
        DocumentoTipo::create([ 'id' => '7', 'nombre' => 'IDENTIFICACION REPRESENTANTE LEGAL', 'codigo' => '007', 'vencimiento' => '1', ]);
        DocumentoTipo::create([ 'id' => '8', 'nombre' => 'DATOS BANCARIOS', 'codigo' => '008', 'vencimiento' => '1', ]);
    }
}
