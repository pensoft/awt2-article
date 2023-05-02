<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Update layout request",
 *      description="Update layout parameters",
 *      type="object",
 *      required={"name", "article_template_id"},
 *      @OA\Xml(
 *         name="UpdateLayoutRequest"
 *      )
 * )
 */
class UpdateLayoutRequest
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
    private $schema_settings;
}
