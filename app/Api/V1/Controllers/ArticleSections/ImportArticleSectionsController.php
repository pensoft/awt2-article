<?php

namespace App\Api\V1\Controllers\ArticleSections;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleSections\CreateArticleSectionRequest;
use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use App\Traits\ArticleSectionsTrait;
use App\Transformers\ArticleSectionTransformer;
use Dingo\Api\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ImportArticleSectionsController extends BaseController
{
    public Collection $createdSections;

    function __construct(){
        $this->createdSections = collect([]);
    }
    use ArticleSectionsTrait;

    public function __invoke(Request $request)
    {
        $uploadedFiles = $request->file('files');

        try {
            DB::beginTransaction();
            foreach ($uploadedFiles as $uploadedFile) {
                $content = json_decode(File::get($uploadedFile->getRealPath()));
                $this->prepareDataFromObject($content);
            }
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new RuntimeException($exception->getMessage());
        }
    }
}
