<?php
namespace App\DTO\Collaborators;

use App\DTO\Articles\ArticleDTO;
use App\DTO\Users\UserDTO;
use Spatie\DataTransferObject\DataTransferObject;

class SendCommentNotificationDTO extends DataTransferObject
{
    public UserDTO $from;

    public UserDTO $to;

    public string $message;

    public ArticleDTO $article;

    public string $hash;

    public string $action = 'sendArticleCommentNotification';


    public static function fromArray(array $data): SendCommentNotificationDTO
    {
        return new self(
            from: new UserDTO($data['from']),
            to: new UserDTO($data['to']),
            message: $data['message'],
            article: new ArticleDTO($data['article']),
            hash: $data['hash']
        );
    }
}
