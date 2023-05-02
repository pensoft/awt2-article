<?php

namespace App\Jobs;

use App\Models\CitationStyle;
use App\Services\CitationStyleService;
use GithubReader\Github\File;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CitationStylesExtractJob
{
    use Dispatchable, SerializesModels;

    /**
     * @var CitationStyleService
     */
    private CitationStyleService $citationStyleService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CitationStyleService $citationStyleService)
    {
        $this->citationStyleService = $citationStyleService;
        $this->citationStyleService->readFiles()->map(fn ($item) => $this->citationStyleService->processItem($item));
    }
}
