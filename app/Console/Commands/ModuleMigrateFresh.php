<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleMigrateFresh extends Command
{
    protected $signature = 'module:migrate-fresh 
                          {module : The name of the module}
                          {--seed : Seed the database after migration}';
    
    protected $description = 'Drop all tables and re-run all migrations for a specific module';

    public function handle()
    {
        $module = strtolower($this->argument('module'));
        $migrationPath = database_path("migrations/{$module}");
        
        if (!File::isDirectory($migrationPath)) {
            $this->error("Migration directory for module '{$module}' does not exist.");
            return 1;
        }

        // Drop all tables in the module's database
        $this->info("Dropping all tables for module '{$module}'...");
        $this->call('db:wipe', [
            '--database' => $module,
            '--force' => true
        ]);

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

        $this->info("Module '{$module}' has been refreshed successfully!");
    }
}
