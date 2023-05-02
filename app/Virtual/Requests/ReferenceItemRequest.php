<?php

namespace App\Virtual\Requests;


/**
 * @OA\Schema(
 *      title="Reference item request",
 *      description="Reference item parameters",
 *      type="object",
 *      required={"label", "schema", "template"},
 *      @OA\Xml(
 *         name="ReferenceItemRequest"
 *      )
 * )
 */
class ReferenceItemRequest
{

    /**
     * @OA\Property(
     *     title="Title",
     *     description="The label of the Reference Item"
     * )
     *
     * @var string
     */
    private string $title;

    /**
     * @OA\Property(
     *     title="Data",
     *     type="object",
     *     @OA\Schema(),
     *     description="he object represent the stored data"
     * )
     * @var array
     */
    private array $data;

    /**
     * @OA\Property(
     *     title="Reference Definition Id",
     *     description="Used Reference Definition/Tyle"
     * )
     * @var string
     */
    private string $reference_definition_id;
}
