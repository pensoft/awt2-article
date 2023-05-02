<?php
namespace App\DTO\Collaborators;

use App\DTO\Articles\ArticleDTO;
use App\DTO\Users\UserDTO;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SendInvitationNotificationDTO extends DataTransferObject
{
    public UserDTO $from;

    public UserDTO $to;

    public string $message;

    public ArticleDTO $article;

    public string $action = 'sendArticleInvitationNotification';

    public string $type;


    /**
     * @throws UnknownProperties
     */
    public static function fromArray(array $data): SendInvitationNotificationDTO
    {
        return new self(
            from: new UserDTO($data['from']),
            to: new UserDTO($data['to']),
            message: $data['message'],
            article: new ArticleDTO($data['article']),
            type: $data['type']
        );
    }
}
