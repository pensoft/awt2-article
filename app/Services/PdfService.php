<?php

namespace App\Services;

use App\DTO\Pdf\PdfExportDTO;
use App\Exceptions\ArticleNotFoundException;
use App\Models\Articles;

class PdfService
{
    public function __construct(
        private ArticleStorageService $articleStorageService,
        private EventDispatcherService $eventDispatcherService
    )
    {
    }

    /**
     * @param $article_id
     * @return object|null
     */
    public function getArticleData($article_id): ?object
    {
        $article = Articles::uuid($article_id);
        if (is_null($article)) {
            throw new ArticleNotFoundException('Requested article not found!');
        }
        return $this->articleStorageService->getArticleData($article_id);
    }

    /**
     * @param $data
     * @return object|null
     */
    public function createTaskForPdfExport($data): ?object
    {
        return $this->eventDispatcherService->dispatchPdfExport($data);
    }
}
