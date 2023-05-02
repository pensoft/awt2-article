<?php
namespace App\Api\V1\Controllers\Collaborators;

use App\Api\V1\Controllers\BaseController;
use App\DTO\Collaborators\AddCollaboratorDTO;
use App\DTO\Collaborators\RemoveCollaboratorDTO;
use App\DTO\Collaborators\SendInvitationNotificationDTO;
use App\Exceptions\ArticleNotFoundException;
use App\Models\ArticleCollaborators;
use App\Services\CollaboratorService;
use App\Transformers\CollaboratorTransformer;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
{
  "article": {
    "id": "b8202bff-5aeb-4e1b-9766-11c58e7e4f9d",
    "title": "Title of the article"
  },
  "message": "Come to be editor",
  "invited": [
    {
        "id": "12345-12345-12345-12345",
        "email": "minco@test.com",
        "name": "Mincho Milev",
        "type": "WRITER"
    }
  ]
}
*/
class InviteCollaboratorController extends BaseController
{
    public function __construct(private CollaboratorService $collaboratorService)
    {
    }

    /**
     * @OA\Post(
     *      path="/collaborators/invite",
     *      operationId="CollaboratorInvite",
     *      tags={"Collaborators"},
     *      summary="Invite collaborators",
     *      description="Invite new collaborators to the article",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CollaboratorInviteRequest")
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
    public function store(Request $request)
    {
        $user = auth()->user();
        $message = $request->get('message', '');
        $invited = collect($request->get('invited', []));
        $article = $request->get('article');

        $this->update($request);

        $invited->each(function($item) use ($article, $user, $message){
            try {
                $this->collaboratorService->sendInviteNotification(new SendInvitationNotificationDTO([
                    'from' => $user->toArray(),
                    'to' => $item,
                    'message' => $message,
                    'article' => $article,
                    'type' => strtolower($item['type']),
                ]));
            } catch (\Exception $exception){
                Log::error($exception->getMessage());
            }
        });
        $collaborators = $this->collaboratorService->getArticleCollaborators($article['id']);

        return $this->response->collection($collaborators, CollaboratorTransformer::class);
    }


    /**
     * @OA\Patch(
     *      path="/collaborators/invite",
     *      operationId="CollaboratorUpdate",
     *      tags={"Collaborators"},
     *      summary="Update collaborators",
     *      description="Update collaborators of the article",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CollaboratorUpdateRequest")
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
    public function update(Request $request)
    {
        $user = auth()->user();
        $invited = collect($request->get('invited', []));
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
        } catch (ArticleNotFoundException $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new RuntimeException($exception->getMessage());
        }

        $collaborators = $this->collaboratorService->getArticleCollaborators($article['id']);

        return $this->response->collection($collaborators, CollaboratorTransformer::class);
    }

    /**
     * @OA\Delete(
     *      path="/collaborators/invite",
     *      operationId="CollaboratorDelete",
     *      tags={"Collaborators"},
     *      summary="Delete collaborators",
     *      description="Delete collaborators assigned to the article",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CollaboratorUpdateRequest")
     *      ),
     *     @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
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
    public function delete(Request $request): \Dingo\Api\Http\Response
    {
        $invited = collect($request->get('invited', []));
        $article = $request->get('article');

        try {
            DB::beginTransaction();
            $invited->each(function ($item) use ($article) {
                $this->collaboratorService->removeCollaborator(RemoveCollaboratorDTO::fromArray([
                    'user_id' => $item['id'],
                    'article' => $article
                ]));
            });
            DB::commit();
        } catch (ArticleNotFoundException $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new RuntimeException($exception->getMessage());
        }

        return $this->response->noContent();
    }
}
