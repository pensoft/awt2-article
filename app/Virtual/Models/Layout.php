<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Create Layout",
 *     description="Create Layout result object",
 *     @OA\Xml(
 *         name="Article"
 *     )
 * )
 */
class Layout extends BaseModels
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The id of the article"
     * )
     *
     * @var integer
     */
    private int $id;

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
     *     title="Version",
     *     description="The version of the layout"
     * )
     * @var int
     */
    private int $version;

    /**
     * @OA\Property(
     *     title="Rules",
     *     type="array",
     *     @OA\Items(type="object"),
     *     description="The array of objects represent the layout's rules."
     * )
     * @var array
     */
    private array $rules;

    /**
     * @OA\Property(
     *     title="Article Template",
     *     type="object",
     *     @OA\Schema(),
     *     description="Used arrticle template"
     * )
     * @var array
     */
    private array $template;

    /**
     * @OA\Property(
     *     title="Citation Style",
     *     type="object",
     *     @OA\Schema(),
     *     description="Used citation style"
     * )
     * @var array
     */
    private array $citationStyle;

    /**
     * @OA\Property(
     *     title="Schema settings"
     * )
     *
     * @var string
     */
    private $schema_settings;

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
