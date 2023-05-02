<?php

namespace App\Transformers;

use App\Models\CitationStyle;
use League\Fractal\TransformerAbstract;

class CitationStyleShortTransformer extends TransformerAbstract
{
    public function transform(CitationStyle $citationStyle)
    {
        return [
            'id'    => (int)$citationStyle->id,
            'title' => (string)$citationStyle->title,
        ];

    }
}
