<?php

namespace App\Virtual\Requests;

use App\Enums\ArticleSectionTypes;

/**
 * @OA\Schema(
 *      title="Update article section request",
 *      description="Create article section parameters",
 *      type="object",
 *      required={"title", "schema", "template", "type"},
 *      @OA\Xml(
 *         name="CreateArticleSectionRequest"
 *      )
 * )
 */
class UpdateArticleSectionRequest
{
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
     *     type="array",
     *     @OA\Items(),
     *     description="The array of objects represent the article section. It can be FormBuilderComponent or Section"
     * )
     * @var array
     */
    private array $schema;

    /**
     * @OA\Property(
     *     title="Template",
     *     description="The template of the article section"
     * )
     *
     * @var string
     */
    private string $template;

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
