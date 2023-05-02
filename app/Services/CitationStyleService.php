<?php

namespace App\Services;

use App\Models\CitationStyle;
use GithubReader\Github\Directory;
use GithubReader\Github\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use ZanySoft\Zip\Facades\Zip;

class CitationStyleService
{

    private string $archiveName;

    private string $unzipFolderName = 'unzip';
    /**
     * @var Directory
     */
    private Directory $githubReader;

    public function __construct(Directory $githubReader)
    {
        $this->githubReader = $githubReader;
        $this->archiveName = $this->githubReader->reader->getRepositoryName().'.zip';
    }

    /*public function readFiles(): Collection
    {
        return $this->githubReader->getFiles()->filter(fn($item) => $this->isCsl($item));
    }*/

    public function readFiles($extractArchive = true): Collection
    {
        if($extractArchive) {
            $this->extractArchive();
            $zip = Zip::open(Storage::disk('local')->path($this->archiveName));
            $zip->extract(Storage::disk('local')->path($this->unzipFolderName));
        }
        $files = Storage::disk('local')->allFiles($this->unzipFolderName);
        return collect($files)->reduce(function($result, $item){
            $fileParts = explode(DIRECTORY_SEPARATOR, $item);
            if (count($fileParts) == 3 && $this->isCsl($item)){
                $result->push($item);
            } else {
                Storage::disk('local')->delete($item);
            }
            return $result;
        }, collect([]));
    }

    private function extractArchive(): void
    {
        $archive = $this->githubReader->reader->extractArchive();
        Storage::disk('local')->put($this->archiveName, $archive);
    }

    public function getContent(File $item)
    {
        return $item->retrieve()->getContent();
    }

    /*public function processItem(string $item): void
    {
        $xml = $this->getContent($item);
        $content = xml_to_array($xml);
        $data = Arr::only($content['info'], ['title', 'title-short', 'updated']);
        $data['style_updated'] = $data['updated'];
        $data['title_short'] = $data['title-short'] ?? null;
        $data['name'] = $item->name;
        $data['content'] = $xml;

        $this->storeCitationStyleData($item->name, $data);
    }*/

    public function processItem(string $item): void
    {
        $fileParts = explode(DIRECTORY_SEPARATOR, $item);
        $fileName = end($fileParts);
        $xml = Storage::disk('local')->get($item);
        $content = xml_to_array($xml);
        $data = Arr::only($content['info'], ['title', 'title-short', 'updated']);

        $data['title_short'] = (!empty($data['title-short']) && !is_array($data['title-short']))? $data['title-short'] : null;
        $data['style_updated'] = (!empty($data['updated']) && !is_array($data['updated']))? $data['updated']: null;
        $data['name'] = $fileName;
        $data['content'] = $xml;
        try {
            $this->storeCitationStyleData($fileName, $data);
        } catch (\Exception $error){
            logger('Error during parsing citation style ['.$item.']: '.$error->getMessage());
        }
    }

    public function getCitationStyleContentByName($name){
        return CitationStyle::where('name', $name)->first()->content;
    }

    private function storeCitationStyleData($name, $data): void
    {

        CitationStyle::updateOrCreate([
            'name' => $name
        ], $data);
    }

    /*private function isCsl($item)
    {
        $name = data_get($item, 'name');

        $suffix = ".csl";
        $length = strlen($suffix);

        return substr_compare($name, $suffix, -$length) === 0;
    }*/

    private function isCsl($item)
    {
        $suffix = ".csl";
        $length = strlen($suffix);

        return substr_compare($item, $suffix, -$length) === 0;
    }
}
