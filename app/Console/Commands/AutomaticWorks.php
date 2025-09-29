<?php

namespace App\Console\Commands;

use App\Http\Controllers\AdminController;
use Illuminate\Console\Command;

class AutomaticWorks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automatic-works';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update worker status based on today shifts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         (new AdminController)->updateTodayStatus();
        $this->info('Worker status updated successfully.');
    }
}
