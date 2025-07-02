<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleMigrateAll extends Command
{
    protected $signature = 'module:migrate-all
                          {module : The name of the module}
                          {--fresh : Refresh the migrations}
                          {--seed : Seed the database after migration}';
    
    protected $description = 'Run all migrations for a specific module';

    public function handle()
    {
        $module = strtolower($this->argument('module'));
        $migrationPath = database_path("migrations/{$module}");
        
        if (!File::isDirectory($migrationPath)) {
            $this->error("Migration directory for module '{$module}' does not exist.");
            return 1;
        }

        if ($this->option('fresh')) {
            $this->call('module:migrate-fresh', [
                'module' => $module,
                '--seed' => $this->option('seed')
            ]);
            return;
        }

        // Run all migrations for the module
        $this->info("Running migrations for module '{$module}'...");
        $this->call('migrate', [
            '--database' => $module,
            '--path' => "database/migrations/{$module}",
            '--force' => true
        ]);

        // Seed if requested
        if ($this->option('seed')) {
            $this->info("Seeding database for module '{$module}'...");
            $this->call('db:seed', [
                '--database' => $module,
                '--class' => ucfirst($module) . 'DatabaseSeeder'
            ]);
        }

        $this->info("Module '{$module}' migrations completed successfully!");
    }
}
