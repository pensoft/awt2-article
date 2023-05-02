<?php
namespace App\Api\V1\Controllers;

use App\Services\MicroserviceConnectorService;
use Dingo\Api\Http\Request;

class Test extends BaseController {

    public function __invoke(Request $request)
    {
        $server = env('API_GATEWAY_SERVICE');
        $res = app(MicroserviceConnectorService::class)->execute($server .'/articles/sections');
        return $res;
    }
}
