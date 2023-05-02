<?php

namespace App\Http\Controllers\CitationStyle;

use App\Services\CitationStyleService;
use Illuminate\Http\Request;

class GetCitationStyleByName
{
    public function __invoke(Request $request, $name, CitationStyleService $citationStyleService){
        $content = $citationStyleService->getCitationStyleContentByName($name);
        return response($content, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
