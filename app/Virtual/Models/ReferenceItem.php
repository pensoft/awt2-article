<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Reference Item",
 *     description="Reference Item object",
 *     @OA\Xml(
 *         name="ReferenceDefinition"
 *     )
 * )
 */
class ReferenceItem extends BaseModels
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The id of the Reference Item"
     * )
     *
     * @var integer
     */
    private int $id;

    /**
     * @OA\Property(
     *     title="Uuid",
     *     description="The uuid of the Reference Item"
     * )
     *
     * @var string
     */
    private string $uuid;

    /**
     * @OA\Property(
     *     title="Title",
     *     description="The title of the Reference Item"
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
     *     description="The object represent the stored data"
     * )
     * @var object
     */
    private object $data;

    /**
     * @OA\Property(
     *     title="Reference Definition Id",
     *     description="Used Reference Definition/Tyle"
     * )
     * @var string
     */
    private string $reference_definition_id;

    /**
     * @OA\Property(
     *     title="Reference Definition Object",
     *     type="object",
     *     @OA\Schema(),
     *     description="Used Reference Definition/Tyle object"
     * )
     * @var object
     */
    private string $reference_definition;

    /**
     * @OA\Property(
     *     title="User",
     *     type="object",
     *     @OA\Schema(),
     *     description="The user which create or last update the item"
     * )
     * @var object
     */
    private string $user;
}
