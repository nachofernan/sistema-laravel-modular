<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SidebarMaintenanceStyle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sidebar:maintenance-style {style : El estilo del indicador (dot, badge, icon)}';

    /**
     * The console description of the console command.
     *
     * @var string
     */
    protected $description = 'Cambia el estilo visual del indicador de mantenimiento en el sidebar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $style = $this->argument('style');
        $validStyles = ['dot', 'badge', 'icon'];

        if (!in_array($style, $validStyles)) {
            $this->error("Estilo inválido. Opciones válidas: " . implode(', ', $validStyles));
            return 1;
        }

        $configPath = config_path('sidebar.php');
        
        if (!file_exists($configPath)) {
            $this->error("El archivo config/sidebar.php no existe.");
            return 1;
        }

        $content = file_get_contents($configPath);
        
        // Reemplazar el estilo en la configuración
        $pattern = "/'style' => '[^']*'/";
        $replacement = "'style' => '{$style}'";
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        if ($newContent === null) {
            $this->error("Error al actualizar el archivo de configuración.");
            return 1;
        }

        file_put_contents($configPath, $newContent);

        $this->info("✅ Estilo del indicador actualizado a: {$style}");
        
        // Mostrar ejemplos visuales
        $this->line("");
        $this->line("📊 Estilos disponibles:");
        $this->line("• dot: Punto rojo pulsante (discreto)");
        $this->line("• badge: Insignia roja con '!' (visible)");
        $this->line("• icon: Icono de advertencia (icónico)");
        
        $this->line("");
        $this->warn("💡 Recuerda limpiar la caché de configuración:");
        $this->line("php artisan config:cache");

        return 0;
    }
} 