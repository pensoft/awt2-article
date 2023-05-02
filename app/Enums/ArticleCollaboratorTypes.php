<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class ArticleCollaboratorTypes extends Enum
{
    const READER =   'reader';
    const COMMENTER =   'commenter';
    const WRITER = 'writer';
}
