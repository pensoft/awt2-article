<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Create new comment request",
 *      description="Create comment parameters",
 *      type="object",
 *      required={"article", "message"},
 *      @OA\Xml(
 *         name="CommentCollaboratorRequest"
 *      )
 * )
 */
class CommentCollaboratorRequest
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
     *     description="The array of the invited collaborators",
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

    /**
     * @OA\Property(
     *     title="mentioned",
     *     description="The array of the mentioned contacts",
     *     type="array",
     *     @OA\Items(
     *             type="object",
     *             @OA\Schema()
     *     ),
     * )
     *
     * @var array
     */
    private array $mentioned;

    /**
     * @OA\Property(
     *     title="hash",
     *     description="The hash of the comment",
     *     type="string",
     * )
     *
     * @var string
     */
    private string $hash;
}
