<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Collaborators update request",
 *      description="Collaborators update parameters",
 *      type="object",
 *      required={"article", "invited"},
 *      @OA\Xml(
 *         name="CollaboratorUpdateRequest"
 *      )
 * )
 */
class CollaboratorUpdateRequest
{
    /**
     * @OA\Property(
     *     title="article",
     *     description="The article object",
     *     type="object",
     *     @OA\Schema(),
     * )
     *
     * @var object
     */
    private object $article;


    /**
     * @OA\Property(
     *     title="invited",
     *     description="The array of the collaborators",
     *     type="array",
     *     @OA\Items(
     *             type="object",
     *             @OA\Schema()
     *     ),
     * )
     *
     * @var array
     */
    private array $invited;
}
