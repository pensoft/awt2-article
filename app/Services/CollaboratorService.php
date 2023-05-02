<?php
namespace App\Services;

use App\DTO\Collaborators\AddCollaboratorDTO;
use App\DTO\Collaborators\RemoveCollaboratorDTO;
use App\DTO\Collaborators\SendCommentNotificationDTO;
use App\DTO\Collaborators\SendInvitationNotificationDTO;
use App\Exceptions\ArticleNotFoundException;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

class CollaboratorService
{
    public function __construct(private EventDispatcherService $eventDispatcherService)
    {}

    /**
     * @param $article_id
     * @return mixed
     */
    public function getArticleCollaborators($article_id): Collection
    {
        try {
            $article = Articles::where('uuid', $article_id)->firstOrFail();

            return $article->collaborators;
        } catch (ModelNotFoundException $exception){
            throw new ArticleNotFoundException('Requested article not found!');
        }
    }

    /**
     * @param AddCollaboratorDTO $dto
     * @return void
     */
    public function storeCollaborator(AddCollaboratorDTO $dto): void
    {

        try {
            $article = Articles::where('uuid', $dto->article_id)->firstOrFail();
            $article->collaborators()->updateOrCreate( Arr::except($dto->toArray(), 'type'),
            [
                'type' => $dto->type
            ]);
        } catch (ModelNotFoundException $exception){
            throw new ArticleNotFoundException('Requested article not found!');
        }
    }

    public function removeCollaborator(RemoveCollaboratorDTO $dto): void
    {
        try {
            $article = Articles::where('uuid', $dto->article->id)->firstOrFail();
            $article->collaborators()
                ->where('user_id', $dto->user_id)
                ->where('article_id', $dto->article->id)
                ->delete();
        } catch (ModelNotFoundException $exception){
            throw new ArticleNotFoundException('Requested article not found!');
        }
    }

    /**
     * @param SendCommentNotificationDTO $dto
     * @return object|null
     */
    public function sendCommentNotification(SendCommentNotificationDTO $dto): ?object
    {
        return $this->eventDispatcherService->dispatchNotification($dto->toArray());
    }

    /**
     * @param SendInvitationNotificationDTO $dto
     * @return object|null
     */
    public function sendInviteNotification(SendInvitationNotificationDTO $dto): ?object
    {
        return $this->eventDispatcherService->dispatchNotification($dto->toArray());
    }
}
