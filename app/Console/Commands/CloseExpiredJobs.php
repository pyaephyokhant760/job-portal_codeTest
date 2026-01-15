<?php

namespace App\Console\Commands;

use App\Models\Work;
use Illuminate\Console\Command;

class CloseExpiredJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-expired-jobs';

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
        Work::where('status', 'Active')
        ->where('expiry_date', '<', now())
        ->update(['status' => 'Expired']);

        $this->info('Expired jobs have been updated successfully.');
    }
}
