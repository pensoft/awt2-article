<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class ArticleStorageService
{
    protected $logger;
    private const ENDPOINT = '/api/article-storage/%s';

    public function __construct(private MicroserviceConnectorService $microserviceConnectorService)
    {
        $this->logger = Log::channel('ArticleStorageService');
    }

    /**
     * @param $id
     * @return object|null
     */
    public function getArticleData($id): ?object
    {
        return $this->execute('article/'. $id);
    }

    /**
     * @param $action
     * @param $data
     * @return object|null
     */
    public function execute($action): ?object
    {
        $uri = env('API_GATEWAY_SERVICE').sprintf(self::ENDPOINT, $action);
        return $this->microserviceConnectorService->execute($uri, 'get');
    }

}
