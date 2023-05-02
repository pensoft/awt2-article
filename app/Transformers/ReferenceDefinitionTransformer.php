<?php

namespace App\Transformers;

use App\Authentication\User;
use App\Models\ReferenceDefinition;
use App\Models\ReferenceItem;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ReferenceDefinitionTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user',
    ];

    public function transform(ReferenceDefinition $referenceDefinition): array
    {
        return [
            'id' => (int) $referenceDefinition->id,
            'title' => (string) $referenceDefinition->title,
            'type' => (string) $referenceDefinition->type,
            'schema' => $referenceDefinition->schema,
            'settings' => $referenceDefinition->settings,
            'created_at' => $referenceDefinition->created_at,
            'updated_at' => $referenceDefinition->updated_at
        ];
    }

    public function includeUser(ReferenceDefinition $referenceDefinition): ?Item
    {
        $user = new User([
            'id' => $referenceDefinition->user_id,
            'name' => $referenceDefinition->user_name,
            'email' => $referenceDefinition->user_email,
        ]);

        if(!$user->id) return null;

        return $this->item($user, new UserTransformer);
    }

}
