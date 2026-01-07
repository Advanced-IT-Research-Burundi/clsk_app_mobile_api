<?php

namespace App\Console\Commands;

use App\Models\Supplier;
use Illuminate\Console\Command;

class seedDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Supplier::factory()->count(10)->create();
        $this->info('Suppliers seeded successfully');
    }
}
