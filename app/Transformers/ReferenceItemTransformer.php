<?php

namespace App\Transformers;

use App\Authentication\User;
use App\Models\ReferenceItem;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;

class ReferenceItemTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user',
        'reference_definition'
    ];
    public function transform(ReferenceItem $referenceItem): array
    {
        return [
            'id' => (int) $referenceItem->id,
            'uuid' => (string) $referenceItem->uuid,
            'title' => (string) $referenceItem->title,
            'data' => $referenceItem->data,
            'reference_definition_id' => (string) $referenceItem->reference_definition_id,
            'created_at' => $referenceItem->created_at,
            'updated_at' => $referenceItem->updated_at
        ];
    }

    public function includeUser(ReferenceItem $referenceItem): ?Item
    {
        $user = new User([
            'id' => $referenceItem->user_id,
            'name' => $referenceItem->user_name,
            'email' => $referenceItem->user_email,
        ]);

        if(!$user->id) return null;

        return $this->item($user, new UserTransformer);
    }

    public function includeReferenceDefinition(ReferenceItem $referenceItem): ?Item
    {
        $referenceDefinition = $referenceItem->reference_definition;
        if(!$referenceDefinition) return null;

        return $this->item($referenceDefinition, new ReferenceDefinitionTransformer);
    }
}
