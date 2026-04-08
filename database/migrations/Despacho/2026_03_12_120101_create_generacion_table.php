<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquinas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        Schema::create('registradores', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 100)->nullable();
            $table->enum('tipo', ['principal', 'respaldo', 'control', 'auxiliar', 'otro'])->default('principal');
            $table->enum('tipo_dato', ['pulsos', 'potencia']);
            $table->unsignedTinyInteger('columna_datos');
            $table->decimal('factor_conversion', 10, 6)->default(1);
            $table->boolean('activo')->default(true);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Tabla pivote sin atributos extra: el tipo es del registrador, no de la relación
        Schema::create('maquina_registrador', function (Blueprint $table) {
            $table->foreignId('maquina_id')->constrained('maquinas')->cascadeOnDelete();
            $table->foreignId('registrador_id')->constrained('registradores')->cascadeOnDelete();
            $table->primary(['maquina_id', 'registrador_id']);
        });

        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registrador_id')->constrained('registradores')->restrictOnDelete();
            $table->date('fecha');
            $table->unsignedTinyInteger('bloque_horario'); // 0 a 23
            $table->time('hora_desde');
            $table->time('hora_hasta');
            $table->decimal('valor_crudo', 12, 4);
            $table->decimal('valor_convertido', 12, 4);
            $table->timestamps();

            $table->unique(['registrador_id', 'fecha', 'bloque_horario', 'hora_hasta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturas');
        Schema::dropIfExists('maquina_registrador');
        Schema::dropIfExists('registradores');
        Schema::dropIfExists('maquinas');
    }
};