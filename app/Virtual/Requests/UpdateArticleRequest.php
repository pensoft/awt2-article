<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Update article request",
 *      description="Update article parameters",
 *      type="object",
 *      required={"name"},
 *      @OA\Xml(
 *         name="UpdateArticleRequest"
 *      )
 * )
 */
class UpdateArticleRequest
{
    /**
     * @OA\Property(
     *     title="Name",
     *     description="The name of the article"
     * )
     *
     * @var string
     */
    private string $name;
}
