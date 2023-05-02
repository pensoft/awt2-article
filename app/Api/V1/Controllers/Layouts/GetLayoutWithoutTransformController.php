<?php

namespace App\Api\V1\Controllers\Layouts;

use App\Http\Controllers\Controller;
use App\Models\Layout;
use App\Transformers\LayoutWithoutTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Helpers;

class GetLayoutWithoutTransformController extends Controller
{
    use Helpers;

    /**
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        try {
            $user = Layout::findOrFail($id);
            return $this->response->item($user, new LayoutWithoutTransformer);
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
