<?php
namespace App\DTO\Collaborators;

use App\DTO\Articles\ArticleDTO;
use Spatie\DataTransferObject\DataTransferObject;

class RemoveCollaboratorDTO extends DataTransferObject
{
    public string $user_id;
    
    public ArticleDTO $article;

    public static function fromArray(array $data): RemoveCollaboratorDTO
    {
        return new self(
            user_id: $data['user_id'],
            article: new ArticleDTO($data['article'])
        );
    }
}
