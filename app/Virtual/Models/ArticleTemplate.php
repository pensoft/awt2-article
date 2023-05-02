<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Create Article Template",
 *     description="Create Article template result object",
 *     @OA\Xml(
 *         name="ArticleTemplate"
 *     )
 * )
 */
class ArticleTemplate extends BaseModels
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
     *     title="Sections",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/ArticleSection"),
     *     description="The array of objects represent the article sections."
     * )
     * @var array
     */
    private array $sections;

    /**
     * @OA\Property(
     *     title="Rules",
     *     type="array",
     *     @OA\Items(
     *          type="object",
     *          @OA\Schema()
     *     ),
     *     description="The array of objects represent the template's rules."
     * )
     * @var array
     */
    private array $rules;
}
