<?php

namespace Database\Seeders;

use App\Models\Concursos\DocumentoTipo;
use App\Models\Concursos\Estado;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConcursosDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Estado::create([ 'id' => '1', 'nombre' => 'Precarga', ]);
        Estado::create([ 'id' => '2', 'nombre' => 'Activado', ]);
        Estado::create([ 'id' => '3', 'nombre' => 'En Análisis', ]);
        Estado::create([ 'id' => '4', 'nombre' => 'Terminado', ]);
        Estado::create([ 'id' => '5', 'nombre' => 'Anulado', ]);

        DocumentoTipo::create([ 'nombre' => 'Pliegos de Bases y Condiciones Generales', ]);
        DocumentoTipo::create([ 'nombre' => 'Pliego de Condiciones Particulares', ]);
        DocumentoTipo::create([ 'nombre' => 'Especificaciones técnicas', ]);
        DocumentoTipo::create([ 'nombre' => 'Planos', ]);
        DocumentoTipo::create([ 'nombre' => 'Anexo I - Seguros', ]);
        DocumentoTipo::create([ 'nombre' => 'Anexo III  Requerimientos SH, Salud Ocupacional y Ambiente', ]);
        DocumentoTipo::create([ 'nombre' => 'Planilla de Cotización', ]);
        DocumentoTipo::create([ 'nombre' => 'Formulario de Declaración Jurada de Garantía Técnica', ]);
        DocumentoTipo::create([ 'nombre' => 'Formulario de Declaración Jurada de Datos del Proveedor.', ]);
        DocumentoTipo::create([ 'nombre' => 'Formulario de DDJJ Programa de Integridad y Política de Anticorrupción', ]);
        DocumentoTipo::create([ 'nombre' => 'Formulario F7-001-02 de Declaración Jurada de Conflicto de Intereses.', ]);
        
        
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Pliegos de Bases y Condiciones Generales', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Pliego de Condiciones Particulares', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Especificaciones técnicas', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Planos', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Anexo I - Seguros', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Anexo III  Requerimientos SH, Salud Ocupacional y Ambiente', ]);
        DocumentoTipo::create(['de_concurso' => false, 'encriptado' => true, 'nombre' => 'Planilla de Cotización', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Formulario de Declaración Jurada de Garantía Técnica', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Formulario de Declaración Jurada de Datos del Proveedor.', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Formulario de DDJJ Programa de Integridad y Política de Anticorrupción', ]);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Formulario F7-001-02 de Declaración Jurada de Conflicto de Intereses.', ]);




        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Memoria técnica descriptiva',  'descripcion' => 'La misma será detallada permitiendo evaluar la calidad, procedimientos técnicos y alcance del suministro propuesto.']);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Identificación del representante legal o mandatario.',  'descripcion' => 'Con poder suficiente para suscribir el presente concurso, los que ya tenemos cargados en nuestra base de datos se encuentran listados en la parte superior de esta página.']);
        DocumentoTipo::create(['de_concurso' => false, 'tipo_documento_proveedor_id' => 5, 'nombre' => 'Instrumentos constitutivos de la sociedad, estatutos, etc.',  'descripcion' => 'Como también actas de asamblea, modificaciones, designación de autoridades, todo otro documento societario debidamente inscripto, que acredite el cumplimiento de los requisitos previsto en los pliegos y la representación del oferente, junto con la constancia de su inscripción en la Inspección General de Justicia o registro público correspondiente.']);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Garantía de Mantenimiento de Oferta',  'descripcion' => 'Según Art. 3 del presente Pliego']);
        DocumentoTipo::create(['de_concurso' => false, 'tipo_documento_proveedor_id' => 4, 'nombre' => 'Constancia de Inscripción en AFIP',  'descripcion' => '']);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Copia de la constancia de inscripción en Impuesto a las Ganancias, Valor Agregado e Ingresos Brutos, según corresponda',  'descripcion' => '']);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Declaración Jurada debidamente suscripta por el OFERENTE o su Apoderado',  'descripcion' => 'En la que manifieste encontrarse al día en el cumplimiento de la totalidad de sus aportes y contribuciones previsionales. (Formulario 931).']);
        DocumentoTipo::create(['de_concurso' => false, 'nombre' => 'Formulario de constancias de visita a los lugares /sitios a instalar los equipos o Formulario de declaración jurada de visita opcional',  'descripcion' => 'Indicando que no solicitaran reclamo de adicionales por desconocimiento del lugar, etc.']);
        DocumentoTipo::create(['de_concurso' => false, 'tipo_documento_proveedor_id' => 6, 'nombre' => 'Balance del último ejercicio económico anual cerrado',  'descripcion' => 'con sus correspondientes cuadros de resultados y anexos, con firmas autógrafas en todas sus hojas del representante legal y dictamen del contador interviniente, certificada su firma por el consejo profesional de ciencias económicas de la jurisdicción en donde se encuentre matriculado, y de ser distinta a la de Provincia de Bs. As., deberá estar la firma del consejo respectivo certificada y legalizada por la F.A.C.P.C.E.']);
    }
}
