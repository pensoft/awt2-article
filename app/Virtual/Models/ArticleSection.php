<?php

namespace App\Virtual\Models;

use App\Enums\ArticleSectionTypes;

/**
 * @OA\Schema(
 *     title="Create Article Section",
 *     description="Create Article Section result object",
 *     @OA\Xml(
 *         name="ArticleSection"
 *     )
 * )
 */
class ArticleSection extends BaseModels
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The id of the article section"
     * )
     *
     * @var integer
     */
    private int $id;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="The name of the article section"
     * )
     *
     * @var string
     */
    private string $name;

    /**
     * @OA\Property(
     *     title="Label",
     *     description="The label of the article section"
     * )
     *
     * @var string
     */
    private string $label;

    /**
     * @OA\Property(
     *     title="Schema",
     *     type="object",
     *     @OA\Schema(),
     *     description="The object represent the FormBuilderComponent"
     * )
     * @var object
     */
    private object $schema;

    /**
     * @OA\Property(
     *     title="Sections",
     *     type="array",
     *     @OA\Items(
     *             type="object",
     *             @OA\Schema()
     *     ),
     *     description="The array of objects represent the article section in complex section"
     * )
     * @var array
     */
    private array $sections;

    /**
     * @OA\Property(
     *     title="Template",
     *     description="The template of the article section"
     * )
     * @var string
     */
    private string $template;

    /**
     * @OA\Property(
     *     title="Compatibility",
     *     type="object",
     *     @OA\Schema()
     * )
     * @var object
     */
    private object $compatibility;

    /**
     * @OA\Property(
     *     title="Type",
     *     description="The type of the article section. It can be simple - 0, or complex - 1"
     * )
     *
     * @var ArticleSectionTypes
     */
    private ArticleSectionTypes $type;
}
