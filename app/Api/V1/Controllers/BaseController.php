<?php
namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use OpenApi\Annotations as OA;

class BaseController extends Controller {
    use Helpers;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Article API Documentation",
     *      description="This documentation you can use to comunicate with the backend part of the project",
     *      @OA\Contact(
     *          email="nikolay.baldziev@scalewest.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Article API Server"
     * )
     *
     * @OAS\SecurityScheme(
     *      securityScheme="passport",
     *      type="http",
     *      scheme="bearer"
     * )
     *
     */
}
