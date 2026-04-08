<?php

namespace Database\Seeders\Despacho;

use App\Models\Despacho\Maquina;
use App\Models\Despacho\Registrador;
use Illuminate\Database\Seeder;

class MaquinasSeeder extends Seeder
{
    public function run(): void
    {
        // ── Registradores ─────────────────────────────────────
        // Se crean independientemente. El tipo es propio del registrador.
        // 'es_principal' original → tipo 'principal' | false → tipo 'respaldo'
        $registradores = [
            ['codigo' => 'NECOM13P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 7.2],
            ['codigo' => 'NECOM13C', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 7.2],
            ['codigo' => 'NECOM14P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 7.2],
            ['codigo' => 'NECOM14C', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 7.2],
            ['codigo' => 'MDAJM21P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.64],
            ['codigo' => 'MDAJM22P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 13.2],
            ['codigo' => 'MP21M21P', 'tipo' => 'principal', 'tipo_dato' => 'potencia', 'columna_datos' => 2, 'factor_conversion' => 1],
            ['codigo' => 'MP21M22P', 'tipo' => 'principal', 'tipo_dato' => 'potencia', 'columna_datos' => 2, 'factor_conversion' => 1],
            ['codigo' => 'MPLAM21P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.64],
            ['codigo' => 'MPLAM21R', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.64],
            ['codigo' => 'MPLAM23P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.88],
            ['codigo' => 'MPLAM25P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.168],
            ['codigo' => 'MPLAM25C', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.168],
            ['codigo' => 'MPLAM26P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.6],
            ['codigo' => 'MPLAM26C', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.6],
            ['codigo' => 'MPLAM11P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.6],
            ['codigo' => 'MPLAM11C', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.6],
            ['codigo' => 'MPLAM12P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.6],
            ['codigo' => 'MPLAM12C', 'tipo' => 'respaldo',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 3.6],
            ['codigo' => 'VGESM24P', 'tipo' => 'principal', 'tipo_dato' => 'potencia', 'columna_datos' => 2, 'factor_conversion' => 1],
            ['codigo' => 'VGESM24C', 'tipo' => 'respaldo',  'tipo_dato' => 'potencia', 'columna_datos' => 2, 'factor_conversion' => 1],
            ['codigo' => 'VGESM21P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.64],
            ['codigo' => 'VGESM23P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.4],
            ['codigo' => 'VGESM22P', 'tipo' => 'principal', 'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 2.64],
            ['codigo' => 'MPLAU11P', 'tipo' => 'auxiliar',  'tipo_dato' => 'pulsos',   'columna_datos' => 4, 'factor_conversion' => 0.36], // nuevo
            ['codigo' => 'MPLAU23P', 'tipo' => 'auxiliar',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 0.024], // nuevo
            ['codigo' => 'MPLAU25P', 'tipo' => 'auxiliar',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 0.0518], // nuevo
            ['codigo' => 'NECOU11R', 'tipo' => 'auxiliar',  'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 1.2], // nuevo
            ['codigo' => 'NECOC11P', 'tipo' => 'otro',      'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 0.96], // nuevo
            ['codigo' => 'NECOM13R', 'tipo' => 'otro',      'tipo_dato' => 'pulsos',   'columna_datos' => 2, 'factor_conversion' => 9.6], // nuevo
        ];

        foreach ($registradores as $r) {
            Registrador::create([
                'codigo'            => $r['codigo'],
                'tipo'              => $r['tipo'],
                'tipo_dato'         => $r['tipo_dato'],
                'columna_datos'     => $r['columna_datos'],
                'factor_conversion' => $r['factor_conversion'],
                'activo'            => true,
            ]);
        }

        // ── Máquinas y asignaciones ───────────────────────────
        $maquinas = [
            // Necochea
            ['codigo' => 'NECOTV03', 'nombre' => 'TV3',  'registradores' => ['NECOM13P', 'NECOM13C', 'NECOU11R', 'NECOC11P', 'NECOM13R']],
            ['codigo' => 'NECOTV04', 'nombre' => 'TV4',  'registradores' => ['NECOM14P', 'NECOM14C', 'NECOU11R', 'NECOC11P', 'NECOM13R']],
            // Mar de Ajó
            ['codigo' => 'MDAJTG15', 'nombre' => 'TG15', 'registradores' => ['MDAJM21P']],
            ['codigo' => 'MDAJTG17', 'nombre' => 'TG17', 'registradores' => ['MDAJM22P']],
            // Mar del Plata
            ['codigo' => 'MDPATG23', 'nombre' => 'TG23', 'registradores' => ['MP21M21P']],
            ['codigo' => 'MDPATG24', 'nombre' => 'TG24', 'registradores' => ['MP21M22P']],
            ['codigo' => 'MDPATG12', 'nombre' => 'TG12', 'registradores' => ['MPLAM21P', 'MPLAM21R']],
            ['codigo' => 'MDPATG19', 'nombre' => 'TG19', 'registradores' => ['MPLAM23P']],
            ['codigo' => 'MDPATG21', 'nombre' => 'TG21', 'registradores' => ['MPLAM25P', 'MPLAM25C', 'MPLAU23P']],
            ['codigo' => 'MDPATG22', 'nombre' => 'TG22', 'registradores' => ['MPLAM26P', 'MPLAM26C', 'MPLAU25P']],
            ['codigo' => 'MDPATV07', 'nombre' => 'TV07', 'registradores' => ['MPLAM11P', 'MPLAM11C', 'MPLAU11P']],
            ['codigo' => 'MDPATV08', 'nombre' => 'TV08', 'registradores' => ['MPLAM12P', 'MPLAM12C', 'MPLAU11P']],
            // Villa Gesell
            ['codigo' => 'VGESTG18', 'nombre' => 'TG18', 'registradores' => ['VGESM24P', 'VGESM24C']],
            ['codigo' => 'VGESTG11', 'nombre' => 'TG11', 'registradores' => ['VGESM21P']],
            ['codigo' => 'VGESTG14', 'nombre' => 'TG14', 'registradores' => ['VGESM23P']],
            ['codigo' => 'VGESTG16', 'nombre' => 'TG16', 'registradores' => ['VGESM22P']],
        ];

        foreach ($maquinas as $m) {
            $maquina = Maquina::create([
                'codigo' => $m['codigo'],
                'nombre' => $m['nombre'],
                'activa' => true,
            ]);

            $ids = Registrador::whereIn('codigo', $m['registradores'])->pluck('id');
            $maquina->registradores()->attach($ids);
        }
    }
}