<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Create new article template request",
 *      description="Create article template parameters",
 *      type="object",
 *      required={"name", "schema"},
 *      @OA\Xml(
 *         name="CreateArticleTemplateRequest"
 *      )
 * )
 */
class CreateArticleTemplateRequest
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
     *     title="Schema",
     *     type="array",
     *     @OA\Items(),
     *     description="The array of article sections"
     * )
     * @var array
     */
    private array $schema;

    /**
     * @OA\Property(
     *     title="Rules",
     *     type="array",
     *     @OA\Items(),
     *     description="The array of objects represent the template's rules."
     * )
     * @var array
     */
    private array $rules;
}
