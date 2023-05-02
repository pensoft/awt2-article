<?php
namespace App\DTO\Collaborators;

use Spatie\DataTransferObject\DataTransferObject;

class AddCollaboratorDTO extends DataTransferObject
{
    public string $user_id;

    public string $user_email;

    public string $user_name;

    public string $added_by;

    public string $article_id;

    public string $type;

    public static function fromArray(array $data): AddCollaboratorDTO
    {
        return new self(
            user_id: $data['user_id'],
            user_email: $data['user_email'],
            user_name: $data['user_name'],
            added_by: $data['added_by'],
            article_id: $data['article_id'],
            type: $data['type']
        );
    }
}
