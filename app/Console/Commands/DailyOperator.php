<?php

namespace App\Console\Commands;

use App\Models\Today;
use Illuminate\Console\Command;

class DailyOperator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operator:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update operators id to null';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Today::query()->update(
            [
            'surgeon_id' => 1,
            'cardiologist_id' => 2,
            ]);
        $this->info('Successfully update operators');
        // return Command::SUCCESS;
    }
}
