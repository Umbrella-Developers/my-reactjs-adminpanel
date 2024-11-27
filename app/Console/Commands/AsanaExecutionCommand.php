<?php

namespace App\Console\Commands;

use App\Jobs\FetchProjectsJob;
use Illuminate\Console\Command;

class AsanaExecutionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:asana-execution-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To run asana execution command for running job in background to fetch updated donations information';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workspaceId = env('ASANA_WORKSPACE_ID');
        $sandboxProjectId = env('SANDBOX_PROCESSED_DDONATIONS_PROJECT');

        // Dispatch the job to fetch projects
        FetchProjectsJob::dispatch($workspaceId, $sandboxProjectId);
        
    }
}
