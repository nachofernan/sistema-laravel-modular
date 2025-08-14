<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class CheckSmtpConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:check-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica la configuración SMTP actual';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔍 Verificando configuración de emails...");
        $this->newLine();

        // Verificar mailer por defecto
        $defaultMailer = config('mail.default');
        $this->info("📧 Mailer por defecto: {$defaultMailer}");

        // Verificar configuración SMTP
        $this->mostrarConfiguracionSmtp();

        // Verificar configuración Microsoft Graph
        $this->mostrarConfiguracionMicrosoftGraph();

        // Verificar configuración global
        $this->mostrarConfiguracionGlobal();

        // Verificar variables de entorno
        $this->verificarVariablesEntorno();

        $this->newLine();
        $this->info("✅ Verificación completada");
    }

    /**
     * Muestra la configuración SMTP
     */
    private function mostrarConfiguracionSmtp()
    {
        $this->newLine();
        $this->info("🔧 Configuración SMTP:");
        
        $smtpConfig = config('mail.mailers.smtp');
        
        $this->line("   Host: " . ($smtpConfig['host'] ?? 'No configurado'));
        $this->line("   Puerto: " . ($smtpConfig['port'] ?? 'No configurado'));
        $this->line("   Encriptación: " . ($smtpConfig['encryption'] ?? 'No configurado'));
        $this->line("   Usuario: " . ($smtpConfig['username'] ?? 'No configurado'));
        $this->line("   Contraseña: " . ($smtpConfig['password'] ? 'Configurada' : 'No configurada'));
        $this->line("   Timeout: " . ($smtpConfig['timeout'] ?? 'No configurado') . 's');
        $this->line("   Local Domain: " . ($smtpConfig['local_domain'] ?? 'No configurado'));
    }

    /**
     * Muestra la configuración Microsoft Graph
     */
    private function mostrarConfiguracionMicrosoftGraph()
    {
        $this->newLine();
        $this->info("📊 Configuración Microsoft Graph:");
        
        $graphConfig = config('mail.mailers.microsoft-graph');
        
        $this->line("   Client ID: " . ($graphConfig['client_id'] ? 'Configurado' : 'No configurado'));
        $this->line("   Client Secret: " . ($graphConfig['client_secret'] ? 'Configurado' : 'No configurado'));
        $this->line("   Tenant ID: " . ($graphConfig['tenant_id'] ? 'Configurado' : 'No configurado'));
        $this->line("   From Address: " . ($graphConfig['from']['address'] ?? 'No configurado'));
        $this->line("   From Name: " . ($graphConfig['from']['name'] ?? 'No configurado'));
        $this->line("   Save to Sent Items: " . ($graphConfig['save_to_sent_items'] ? 'Sí' : 'No'));
    }

    /**
     * Muestra la configuración global
     */
    private function mostrarConfiguracionGlobal()
    {
        $this->newLine();
        $this->info("🌐 Configuración Global:");
        
        $this->line("   From Address: " . config('mail.from.address'));
        $this->line("   From Name: " . config('mail.from.name'));
        $this->line("   Automated Sending: " . (config('mail.automated_sending_enabled') ? 'Habilitado' : 'Deshabilitado'));
        $this->line("   Domain Filter: " . (config('mail.domain_filter_enabled') ? 'Habilitado' : 'Deshabilitado'));
        $this->line("   Sending Interval: " . config('mail.sending_interval') . 's');
        $this->line("   Rate Limit: " . config('mail.rate_limit_per_minute') . ' emails/min');
    }

    /**
     * Verifica las variables de entorno
     */
    private function verificarVariablesEntorno()
    {
        $this->newLine();
        $this->info("🔑 Variables de Entorno (.env):");
        
        $variables = [
            'MAIL_MAILER' => env('MAIL_MAILER'),
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD') ? 'Configurada' : 'No configurada',
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
            'MAIL_MICROSOFT_GRAPH_CLIENT_ID' => env('MAIL_MICROSOFT_GRAPH_CLIENT_ID') ? 'Configurado' : 'No configurado',
            'MAIL_MICROSOFT_GRAPH_CLIENT_SECRET' => env('MAIL_MICROSOFT_GRAPH_CLIENT_SECRET') ? 'Configurado' : 'No configurado',
            'MAIL_MICROSOFT_GRAPH_TENANT_ID' => env('MAIL_MICROSOFT_GRAPH_TENANT_ID') ? 'Configurado' : 'No configurado',
        ];

        foreach ($variables as $variable => $valor) {
            $status = $valor ? '✅' : '❌';
            $this->line("   {$status} {$variable}: " . ($valor ?: 'No configurado'));
        }
    }
} 