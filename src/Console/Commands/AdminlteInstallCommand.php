<?php

namespace Laraeast\AdminLte\Console\Commands;

use Illuminate\Console\Command;
use Laraeast\AdminLte\Preset\AdminLte;

class AdminlteInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->assets();
    }

    protected function assets()
    {
        AdminLte::install();

        $this->info('AdminLte scaffolding installed successfully.');
        $this->comment('Please run "npm install && npm run adminlte:dev" to compile your fresh scaffolding.');
    }
}
