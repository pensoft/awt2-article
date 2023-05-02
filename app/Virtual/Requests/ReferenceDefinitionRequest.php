<?php

namespace App\Virtual\Requests;


/**
 * @OA\Schema(
 *      title="Reference definition request",
 *      description="Reference definition parameters",
 *      type="object",
 *      required={"label", "schema", "template"},
 *      @OA\Xml(
 *         name="ReferenceDefinitionRequest"
 *      )
 * )
 */
class ReferenceDefinitionRequest
{

    /**
     * @OA\Property(
     *     title="Title",
     *     description="The label of the Reference Definition"
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
     *     type="array",
     *     @OA\Items(),
     *     description="The array of objects represent the Reference Definition. It can be FormBuilderComponent or Section"
     * )
     * @var array
     */
    private array $schema;

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
