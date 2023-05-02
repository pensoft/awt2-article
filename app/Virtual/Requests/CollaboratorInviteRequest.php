<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Collaborator invitation request",
 *      description="Collaborator invitation parameters",
 *      type="object",
 *      required={"article", "message", "invited"},
 *      @OA\Xml(
 *         name="CollaboratorInviteRequest"
 *      )
 * )
 */
class CollaboratorInviteRequest
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
     *     title="message",
     *     description="The layout of the article",
     *     type="string",
     * )
     *
     * @var string
     */
    private string $message;

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
