<?php
namespace App\DTO\Pdf;

use Spatie\DataTransferObject\DataTransferObject;
use Dingo\Api\Http\Request;

class PdfExportDTO extends DataTransferObject
{
    public array $articleSectionsStructure;

    public array $articleSectionsStructureFlat;

    public object | array $sectionFromGroupsData;

    public object | array $sectionPMNodesJson;

    public object | array $articleCitatsObj;

    public object | array $ArticleFigures;

    public object | array $headerPmNodesJson;

    public object | array $footerPmNodesJson;

    public object | array $figuresTemplates;

    public object | array $pdfSettings;

    public array $ArticleFiguresNumbers;

    public array $ArticleTablesNumbers;

    public object | array $ArticleTables;

    public object | array $tablesTemplates;

    public object | array $elementsCitations;

    public object | array $dataURLObj;

    public object | array $pdfPrintSettings;

    public object | array $references;

    public object | array $customPropsObj;

    public object | array $externalRefs;

    public object | array $referencesInEditor;

    public object | array $localRefs;

    public object | array $trackChangesMetadata;

    public object | array $articleComments;
    public object | array $supplementaryFiles;
    public object | array $supplementaryFilesTemplates;
    public object | array $supplementaryFilesNumbers;
    public object | array $endNotes;
    public object | array $endNotesNumbers;
    public object | array $endNotesTemplates;

    public string $action = 'export';

    public static function fromRequest($articleData, Request $request)
    {
        $requestJson = json_decode($request->getContent());
        return new self(
            endNotesTemplates: $articleData->endNotesTemplates,
            endNotesNumbers: $articleData->endNotesNumbers,
            endNotes: $articleData->endNotes,
            supplementaryFilesNumbers: $articleData->supplementaryFilesNumbers,
            supplementaryFilesTemplates: $articleData->supplementaryFilesTemplates,
            supplementaryFiles: $articleData->supplementaryFiles,
            articleSectionsStructure: $articleData->articleSectionsStructure,
            articleSectionsStructureFlat: $articleData->articleSectionsStructureFlat,
            sectionFromGroupsData: $articleData->sectionFromGroupsData ?? (object)[],
            sectionPMNodesJson: $articleData->sectionPMNodesJson,
            ArticleFigures: $articleData->ArticleFigures,
            ArticleFiguresNumbers: $articleData->ArticleFiguresNumbers,
            ArticleTablesNumbers: $articleData->ArticleTablesNumbers,
            tablesTemplates: $articleData->tablesTemplates,
            ArticleTables: $articleData->ArticleTables,
            elementsCitations: $articleData->elementsCitations,
            articleCitatsObj: $articleData->articleCitatsObj,
            figuresTemplates: $articleData->figuresTemplates,
            dataURLObj: $articleData->dataURLObj,
            pdfPrintSettings: $articleData->pdfPrintSettings,
            customPropsObj: $articleData->customPropsObj,
            references: $articleData->references,
            externalRefs: $articleData->externalRefs,
            localRefs: $articleData->localRefs,
            articleComments: $articleData->articleComments,
            trackChangesMetadata: $articleData->trackChangesMetadata,
            referencesInEditor: $articleData->referencesInEditor,
            headerPmNodesJson: $requestJson->headerPmNodesJson ?? (object)[],
            footerPmNodesJson: $requestJson->footerPmNodesJson ?? (object)[],
            pdfSettings: $requestJson->pdfSettings ?? (object)[],
        );
    }
}
