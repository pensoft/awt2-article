<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Create new layout request",
 *      description="Create layout parameters",
 *      type="object",
 *      required={"name", "article_template_id"},
 *      @OA\Xml(
 *         name="CreateLayoutsRequest"
 *      )
 * )
 */
class CreateLayoutRequest
{
    /**
     * @OA\Property(
     *     title="Name",
     *     description="The name of the article"
     * )
     *
     * @var string
     */
    private string $name;


    /**
     * @OA\Property(
     *     title="Article Template Id",
     *     description="The template of the article"
     * )
     *
     * @var integer
     */
    private int $article_template_id;

    /**
     * @OA\Property(
     *     title="Citation Style Id",
     *     description="The citation style of the layout"
     * )
     *
     * @var integer
     */
    private int $citation_style_id;

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

    /**
     * @OA\Property(
     *     title="Schema settings"
     * )
     *
     * @var string
     */
    private string $schema_settings;

    /**
     * @OA\Property(
     *     title="Layout settings",
     *     type="object",
     *     @OA\Schema(),
     * )
     *
     * @var object
     */
    private object $settings;
}
