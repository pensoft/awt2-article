<?php

namespace App\Console\Commands;

use App\Jobs\CitationStylesExtractJob;
use Illuminate\Console\Command;

class CitationStylesExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:citation-styles:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract citation style definitions';

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
     * @return int
     */
    public function handle()
    {
        CitationStylesExtractJob::dispatch();
    }
}
