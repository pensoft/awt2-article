<?php
namespace App\Transformers;

use App\Models\ArticleCollaborators;
use League\Fractal\TransformerAbstract;

class CollaboratorTransformer extends TransformerAbstract
{
    public function transform(ArticleCollaborators $item)
    {
        return [
            'user_id' => $item->user_id,
            'user_name' => $item->user_name,
            'user_email' => $item->user_email,
            'type' => $item->type->key
        ];
    }
}
