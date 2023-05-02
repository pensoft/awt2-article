<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Reference Definition",
 *     description="Reference Definition object",
 *     @OA\Xml(
 *         name="ReferenceDefinition"
 *     )
 * )
 */
class ReferenceDefinition extends BaseModels
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The id of the Reference Definition"
     * )
     *
     * @var integer
     */
    private int $id;

    /**
     * @OA\Property(
     *     title="Title",
     *     description="The title of the Reference Definition"
     * )
     *
     * @var string
     */
    private string $title;

    /**
     * @OA\Property(
     *     title="Type",
     *     description="The type of the Reference Definition"
     * )
     *
     * @var string
     */
    private string $type;

    /**
     * @OA\Property(
     *     title="Schema",
     *     type="object",
     *     @OA\Schema(),
     *     description="The object represent the FormBuilderComponents"
     * )
     * @var object
     */
    private object $schema;

    /**
     * @OA\Property(
     *     title="Settings",
     *     type="object",
     *     @OA\Schema(),
     *     description="The settings of the Reference Definition"
     * )
     * @var object
     */
    private string $settings;
}
