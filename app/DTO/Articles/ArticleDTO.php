<?php
namespace App\DTO\Articles;

use Spatie\DataTransferObject\DataTransferObject;

class ArticleDTO extends DataTransferObject
{
    public string $id;

    public string $title;
}
