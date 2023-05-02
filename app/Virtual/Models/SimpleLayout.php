<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="SimpleLayout",
 *     description="Simple Layout result object",
 *     @OA\Xml(
 *         name="SimpleLayout"
 *     )
 * )
 */
class SimpleLayout extends BaseModels
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The id of the layout"
     * )
     *
     * @var integer
     */
    private int $id;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="The name of the layout"
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
}
