<?php

namespace App\Transformers;

use App\Authentication\User;
use App\Models\Articles;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ArticleShortTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'layout', 'user', 'collaborators'
    ];

    public function transform(Articles $article): array
    {
        return [
            'id' => (int) $article->id,
            'uuid' => (string) $article->uuid,
            'name' => (string) $article->name,
            'created_at' => $article->created_at,
            'updated_at' => $article->updated_at
        ];
    }

    public function includeTemplate(Articles $article): ?Item
    {
        $template = $article->getTemplate();

        if(!$template) return null;

        return $this->item($template, new ArticleTemplateTransformer);
    }

    public function includeLayout(Articles $article): ?Item
    {
        $layout = $article->layout;

        if(!$layout) return null;

        return $this->item($layout, new LayoutShortTransformer());
    }

    public function includeCollaborators(Articles $article): ?Collection
    {
        $collaborators = $article->collaborators;

        if(!$collaborators) return null;

        return $this->collection($collaborators, new CollaboratorTransformer);
    }

    public function includeUser(Articles $article): ?Item
    {
        $user = new User([
            'id' => $article->user_id,
            'name' => $article->user_name,
            'email' => $article->user_email,
        ]);

        if(!$user->id) return null;

        return $this->item($user, new UserTransformer);
    }
}
