<?php

namespace App\Transformers;

use App\Models\CitationStyle;
use League\Fractal\TransformerAbstract;

class CitationStyleTransformer extends TransformerAbstract
{
    public function transform(CitationStyle $citationStyle)
    {
        return [
            'id'    => (int)$citationStyle->id,
            'name'  => (string)$citationStyle->name,
            'title' => (string)$citationStyle->title,
            'title_short' => (string)$citationStyle->title_short,
            'style_updated' => $citationStyle->style_updated,
            'url' => route('web.get-citation-style', ['name'=>$citationStyle->name]),
            'style_content' => $citationStyle->content
        ];

    }
}
