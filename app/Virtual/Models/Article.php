<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Create Article",
 *     description="Create Article result object",
 *     @OA\Xml(
 *         name="Article"
 *     )
 * )
 */
class Article extends BaseModels
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
     *     title="Uuid",
     *     description="The uuid of the article"
     * )
     *
     * @var string
     */
    private string $uuid;

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
     *     title="Sections",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Layout"),
     *     description="The array of objects represent the article layout."
     * )
     * @var array
     */
    private array $layout;

    /**
     * @OA\Property(
     *     title="User's id",
     *     description="The Id of the user created the article"
     * )
     * @var integer
     */
    private int $user_id;
}
