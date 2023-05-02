<?php
namespace App\Api\V1\Controllers\Collaborators;

use App\Api\V1\Controllers\BaseController;
use App\Authentication\User;
use App\DTO\Collaborators\AddCollaboratorDTO;
use App\DTO\Collaborators\SendCommentNotificationDTO;
use App\DTO\Users\UserDTO;
use App\Exceptions\ArticleNotFoundException;
use App\Models\ArticleCollaborators;
use App\Services\CollaboratorService;
use App\Transformers\CollaboratorTransformer;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
{
  "article": {
    "id": "b8202bff-5aeb-4e1b-9766-11c58e7e4f9d",
    "title": "Title of the article"
  },
  "message": "@minco@test.com hi, @nick@test.com is already here",
  "invited": [
    {
        "id": "12345-12345-12345-12345",
        "email": "minco@test.com",
        "name": "Mincho Milev",
        "type": "WRITER"
    }
  ],
  "mentioned": [
    {
        "id": "12345-12345-12345-12345",
        "email": "nick@test.com",
        "name": "Nikolay Baldzhiev"
    }
  ],
  "hash": "d58e3582afa99040e27b92b13c8f2280"
}
*/
class CommentCollaboratorController extends BaseController
{
    public function __construct(private CollaboratorService $collaboratorService)
    {
    }

    /**
     * @OA\Post(
     *      path="/collaborators/comment",
     *      operationId="CommentCollaborator",
     *      tags={"Collaborators"},
     *      summary="Comment article with invitation and mentioning",
     *      description="Create a new comment and invite collaborators or mentioning",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CommentCollaboratorRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Collaborator"),
     *              ),
     *            )
     *          )
     *       ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $hash = $request->get('hash', '');
        $message = $request->get('message', '');
        $invited = collect($request->get('invited', []));
        $mentioned = collect($request->get('mentioned', []));
        $article = $request->get('article');

        try {
            DB::beginTransaction();
            $invited->each(function ($item) use ($user, $article) {
                $this->collaboratorService->storeCollaborator(AddCollaboratorDTO::fromArray([
                    'user_id' => $item['id'],
                    'user_name' => $item['name'],
                    'user_email' => $item['email'],
                    'added_by' => $user->id,
                    'article_id' => $article['id'],
                    'type' => strtolower($item['type']),
                ]));
            });
            DB::commit();
        } catch (ArticleNotFoundException $exception){
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new RuntimeException($exception->getMessage());
        }

        $invited->merge($mentioned)->each(function($item) use ($article, $user, $hash, $message){
            try {
                $this->collaboratorService->sendCommentNotification(new SendCommentNotificationDTO([
                    'from' => $user->toArray(),
                    'to' => $item,
                    'message' => $message,
                    'article' => $article,
                    'hash' => $hash
                ]));

            } catch (\Exception $exception){
                Log::error($exception->getMessage());
            }
        });

        $collaborators = $this->collaboratorService->getArticleCollaborators($article['id']);

        return $this->response->collection($collaborators, CollaboratorTransformer::class);
    }
}
