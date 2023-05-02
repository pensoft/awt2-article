<?php

namespace App\Services;

use App\Enums\HttpStatusCode;
use Dingo\Api\Http\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MicroserviceConnectorService
{
    const UNAUTHORIZED = 401;

    protected $logger;

    protected $cacheStore;


    protected $cacheTags;

    private const ENPOINTS = [
        'register' => '/api/service-register',
        'token'    => '/api/service-token',
        'refresh'  => '/api/service-refresh-token',
    ];

    public function __construct(array $tags = [], ?string $store = null)
    {
        $this->cacheTags = $tags;
        $this->cacheStore = $store ?? \config('cache.default');
        $this->logger = Log::channel('MicroserviceConnectorService');
    }

    /**
     * @param $uri
     * @param $method
     * @param $attempts
     * @param $data
     * @param $async
     * @return object|string|null
     */
    public function execute($uri, $method = 'get', $attempts = 0, $data = [], $async = false): object|string|null
    {
        $method = strtolower($method);
        try {
            $tokens = $this->auth();

            $request = app(Request::class);
            $user = $request->user();
            $data = [
                'data'=>$data,
                'user' => $user->toArray()
            ];

            $http = $this->createHttp();
            $http = $this->setAuth($http, $tokens->access_token);
            $http = $this->verify($http);

            $response = $this->call($http, $method, $uri, $data);

            if (in_array($response->getStatusCode(), [HttpStatusCode::UNAUTHORIZED, HttpStatusCode::FORBIDDEN]) && $attempts <= 1) {
                $this->refreshToke($attempts);
                $attempts++;
                return $this->execute($uri, $method = 'get', $attempts, $data);
            }
            /*if($async) {
                $response = Http::async()
                    ->withoutVerifying()
                    ->withToken($tokens->access_token)
                    ->acceptJson();
            } else {
                $response = Http::contentType('application/json')
                    ->withoutVerifying()
                    ->withToken($tokens->access_token);
            }

            switch ($method){
                case 'post':
                case 'put':
                $response->{strtolower($method)}($uri, $data);
                    break;
                default:
                    $response->{strtolower($method)}($uri, $data);
                    break;
            }*/

            return $this->isJson($response)? $response->object() : $response->body();

        } catch (TooManyRedirectsException $e) {
            // handle too many redirects
            $this->logger->error('TooManyRedirectsException', [$uri, $method, $data]);
        } catch (ClientException|ServerException $e) {
            // ClientException - A GuzzleHttp\Exception\ClientException is thrown for 400 level errors if the http_errors request option is set to true.
            // ServerException - A GuzzleHttp\Exception\ServerException is thrown for 500 level errors if the http_errors request option is set to true.
            $statusCode = 0;
            if ($e->hasResponse()) {
                // is HTTP status code, e.g. 500
                $statusCode = $e->getResponse()->getStatusCode();
            }
            if ($statusCode == HttpStatusCode::UNAUTHORIZED && !$attempts) {
                $this->refreshToke();
                $attempts++;
                return $this->execute($uri, $method = 'get', $attempts, $data);
            }

            $this->logger->error('ClientException [' . $statusCode . '|' . $e->getMessage() . ']', [$uri, $method, $data]);
        } catch (ConnectException $e) {
            // ConnectException - A GuzzleHttp\Exception\ConnectException exception is thrown in the event of a networking error. This may be any libcurl error, including certificate problems
            $handlerContext = $e->getHandlerContext();
            if ($handlerContext['errno'] ?? 0) {
                // this is the libcurl error code, not the HTTP status code!!!
                // for example 6 for "Couldn't resolve host"
                $errno = (int)($handlerContext['errno']);
            }
            // get a description of the error (which will include a link to libcurl page)
            $errorMessage = $handlerContext['error'] ?? $e->getMessage();
            $this->logger->error('[ConnectException] ' . $errorMessage, [$handlerContext, $uri, $method]);
        } catch (\Exception $e) {
            // fallback, in case of other exception
            $this->logger->error('[Exception] ' . $e->getMessage(), [$uri, $method]);
        }

        return null;
    }

    private function isJson(Response $response){
        return strpos($response->header('Content-Type'),'json') !== false;
    }

    private function verify(PendingRequest $request): PendingRequest
    {
        return $request->withoutVerifying();
    }

    private function setAuth(PendingRequest $request, $token = ''){
        return $request->withToken($token);
    }

    private function createHttp($type = 'application/json'){
        return Http::log()->contentType($type);
    }

    private function call(PendingRequest $request, $method, $url, $params){
        switch (strtoupper($method)) {
            case 'GET':
                return $request->get($url);
            case 'HEAD':
                return $request->head($url, $params);
            default:
            case 'POST':
                return $request->post($url, $params);
            case 'PATCH':
                return $request->patch($url, $params);
            case 'PUT':
                return $request->put($url, $params);
            case 'DELETE':
                return $request->delete($url, $params);
        }
    }

    protected function refreshToke($forget = false)
    {
        $tokens = $this->serviceRefresh();

        if($forget || @$tokens->status_code == 500){
            $this->cacheStore()->forget($this->getServiceName());
            $this->auth();
            return $this->refreshToke();
        }
        logger('TOKEN');
        logger(print_r($tokens, true));
        if (@$tokens->error) {
            $this->logger->emergency($tokens->error_description, (array)$this->cacheStore()->get($this->getServiceName()));
            throw new \InvalidArgumentException($tokens->error_description);
        }

        $this->cacheStore()->put(
            $this->getServiceName(),
            $tokens,
            \now()->addSeconds($tokens->expires_in)
        );

        return $tokens;
    }


    public function auth()
    {
        $token = $this->cacheStore()->get($this->getServiceName());

        if (!$token) {
            $key = $this->getServiceName();
            $tokens = $this->serviceRegister();

            return $this->cacheStore()->remember(
                $key,
                \now()->addSeconds($tokens->expires_in),
                function () use ($tokens) {
                    return $tokens;
                }
            );
        }
        return $token;
    }

    private function serviceRefresh()
    {
        $key = $this->getServiceName();
        $oldTokens = $this->cacheStore()->get($key);

        return Http::withToken($oldTokens->access_token)
            ->withoutVerifying()
            ->acceptJson()
            ->post(
                env('AUTH_SERVICE').self::ENPOINTS['refresh'],
                [
                    'refresh_token' => $oldTokens->refresh_token,
                ]
            )->object();

    }

    public function serviceRegister()
    {

        return Http::withoutVerifying()
            ->acceptJson()
            ->post(
                env('AUTH_SERVICE').self::ENPOINTS['register'],
                [
                    'name'     => $this->getServiceName().'-'.$this->generateRandomString(4),
                    'password' => $this->generateRandomString(8),
                ]
            )->object();
    }

    protected function getServiceName()
    {
        return env('APP_NAME', 'Article');
    }

    public function cacheStore(): Repository
    {
        $store = Cache::store($this->cacheStore);

        return $store->getStore() instanceof TaggableStore ? $store->tags($this->cacheTags) : $store;
    }

    private function generateRandomString(int $n = 0): string
    {
        $al = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'
               , 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
               'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E',
               'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
               'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
               '0', '2', '3', '4', '5', '6', '7', '8', '9'];

        $len = !$n ? random_int(7, 12) : $n; // Chose length randomly in 7 to 12

        $ddd = array_map(function ($a) use ($al) {
            $key = random_int(0, 60);
            return $al[$key];
        }, array_fill(0, $len, 0));
        return implode('', $ddd);
    }
}
