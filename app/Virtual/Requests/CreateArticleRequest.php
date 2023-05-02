<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Create new article request",
 *      description="Create article parameters",
 *      type="object",
 *      required={"name", "article_template_id"},
 *      @OA\Xml(
 *         name="CreateArticleRequest"
 *      )
 * )
 */
class CreateArticleRequest
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


    /**
     * @OA\Property(
     *     title="Layout Id",
     *     description="The layout of the article"
     * )
     *
     * @var integer
     */
    private int $layout_id;
}
