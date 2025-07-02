<?php

namespace App\Exports\Proveedores;

use App\Models\Proveedores\DocumentoTipo;
use App\Models\Proveedores\Proveedor;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

// class ProveedorExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromQuery, ShouldAutoSize, WithHeadings
class ProveedorExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromArray, ShouldAutoSize, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    * 
    */

    /**
    * @method void download()
    */
    /* public function collection()
    {

        return Proveedor::all();
    } */

    use Exportable;

    public function headings(): array
    {
        $docs = DocumentoTipo::orderBy('codigo')->get()->pluck('nombre')->toArray();
        return array_merge([
            'Número ID',
            'CUIT',
            'Razón Social',
            'Estado',
            'Fantasía',
            'Correo Electrónico',
            'Teléfono',
            'Página Web',
            'Horario',
        ], $docs);
    }

    public function query()
    {
        return Proveedor::select('id', 'cuit','razonsocial','estado_id','fantasia','correo','telefono','webpage','horario')->where('estado_id', '1');
    }

    public function array(): array
    {
        $ahora = \Carbon\Carbon::now();
        $ps = Proveedor::where('estado_id', '!=', '4')->get();
        $retorno = array();
        foreach($ps as $p) {
            //$estado = $p->provestado_id == '1' ? 'Pre-Activado' : 'Activado';
            $estado = 'Nivel ' . $p->estado_id;
            $pa = [$p->id, $p->cuit, $p->razonsocial, $estado, $p->fantasia, $p->correo, $p->telefono, $p->webpage, $p->horario];
            $prov_docus = array();
            foreach(DocumentoTipo::orderBy('codigo')->get() as $tipo_documento) {
                $prov_ult_docu = $p->ultimo_documento($tipo_documento);
                if($prov_ult_docu) {
                    if($tipo_documento->vencimiento == 0) {
                        $prov_docus[] = 'Si';
                    } else { 
                        if($prov_ult_docu->vencimiento == null) {
                            $prov_docus[] = 'Si (sin especificar)';
                        } elseif (\Carbon\Carbon::create($prov_ult_docu->vencimiento)->isPast()) {
                            $prov_docus[] = 'Si (vencido)';
                        } else {
                            $prov_docus[] = 'Si (al día)';
                        }
                    }
                } else {
                    $prov_docus[] = '';
                }
            }
            $retorno[] = array_merge($pa, $prov_docus);
        }
        return $retorno;
        //return Proveedor::select('id', 'cuit','razonsocial','fantasia','correo','telefono','webpage','horario')->where('estado_id', '1');
    }
}
