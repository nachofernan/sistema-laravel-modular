<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TestEmail;

class TestEmailSmtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-smtp 
                            {email : Email de destino para la prueba}
                            {--from= : Email de origen (opcional)}
                            {--subject= : Asunto del email (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el envío de emails via SMTP de Office 365';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $destinatario = $this->argument('email');
        $from = $this->option('from') ?? config('mail.from.address');
        $subject = $this->option('subject') ?? 'Test SMTP - Buenos Aires Energía';

        $this->info("🚀 Iniciando prueba de envío de email...");
        $this->info("📧 Destinatario: {$destinatario}");
        $this->info("📤 Desde: {$from}");
        $this->info("📋 Asunto: {$subject}");
        $this->info("⚙️  Mailer configurado: " . config('mail.default'));
        $this->newLine();

        // Mostrar configuración SMTP actual
        $this->mostrarConfiguracionSmtp();

        // Confirmar antes de enviar
        if (!$this->confirm('¿Deseas proceder con el envío del email de prueba?')) {
            $this->warn('❌ Prueba cancelada por el usuario');
            return 1;
        }

        try {
            $this->info("📤 Enviando email...");
            
            // Crear email de prueba
            $testEmail = new TestEmail($subject, $destinatario);
            
            // Enviar email
            Mail::to($destinatario)->send($testEmail);
            
            $this->info("✅ Email enviado exitosamente!");
            $this->info("📊 Revisa la bandeja de entrada de: {$destinatario}");
            
            // Log del éxito
            Log::info('Test SMTP exitoso', [
                'destinatario' => $destinatario,
                'mailer' => config('mail.default'),
                'timestamp' => now()
            ]);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al enviar email:");
            $this->error($e->getMessage());
            
            // Log del error
            Log::error('Test SMTP fallido', [
                'destinatario' => $destinatario,
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
                'timestamp' => now()
            ]);
            
            // Mostrar información de debugging
            $this->mostrarInfoDebugging($e);
            
            return 1;
        }
    }

    /**
     * Muestra la configuración SMTP actual
     */
    private function mostrarConfiguracionSmtp()
    {
        $this->info("🔧 Configuración SMTP actual:");
        $this->line("   Host: " . config('mail.mailers.smtp.host'));
        $this->line("   Puerto: " . config('mail.mailers.smtp.port'));
        $this->line("   Encriptación: " . config('mail.mailers.smtp.encryption'));
        $this->line("   Usuario: " . config('mail.mailers.smtp.username'));
        $this->line("   Timeout: " . config('mail.mailers.smtp.timeout') . 's');
        $this->newLine();
    }

    /**
     * Muestra información útil para debugging
     */
    private function mostrarInfoDebugging($exception)
    {
        $this->newLine();
        $this->warn("🔍 Información para debugging:");
        
        if (str_contains($exception->getMessage(), 'authentication')) {
            $this->line("   • Verifica que el usuario y contraseña sean correctos");
            $this->line("   • Si tienes 2FA habilitado, usa una App Password");
            $this->line("   • Si no tienes 2FA, usa tu contraseña normal");
        }
        
        if (str_contains($exception->getMessage(), 'connection')) {
            $this->line("   • Verifica que el host y puerto sean correctos");
            $this->line("   • Revisa si hay firewall bloqueando la conexión");
        }
        
        if (str_contains($exception->getMessage(), 'encryption')) {
            $this->line("   • Verifica que la encriptación sea 'tls'");
            $this->line("   • Asegúrate de que el puerto sea 587");
        }
        
        $this->line("   • Revisa los logs en: storage/logs/laravel.log");
        $this->line("   • Verifica la configuración en .env");
    }
} 