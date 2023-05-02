<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class EventDispatcherService
{
    protected $logger;
    private const ENDPOINT = '/api/event-dispatcher/%s';

    public function __construct(private MicroserviceConnectorService $microserviceConnectorService)
    {
        $this->logger = Log::channel('EventDispatcherService');
    }

    /**
     * @param $data
     * @return object|null
     */
    public function dispatchNotification($data): ?object
    {
        return $this->execute('notifications', $data);
    }

    public function dispatchPdfExport($data): ?object
    {
        return $this->execute('pdf/export', $data);
    }

    /**
     * @param $action
     * @param $data
     * @return object|null
     */
    public function execute($action, $data): ?object
    {
        $uri = env('API_GATEWAY_SERVICE').sprintf(self::ENDPOINT, $action);
        return $this->microserviceConnectorService->execute($uri, 'post', 0, $data);
    }

}
