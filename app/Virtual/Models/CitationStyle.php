<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Citation Style",
 *     description="Citation Style result object",
 *     @OA\Xml(
 *         name="Citation Style"
 *     )
 * )
 */
class CitationStyle extends BaseModels
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The id of the citation style"
     * )
     *
     * @var integer
     */
    private int $id;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="The name of the citation style"
     * )
     *
     * @var string
     */
    private string $name;

    /**
     * @OA\Property(
     *     title="Title",
     *     description="The title of the citation style"
     * )
     * @var string
     */
    private string $title;

    /**
     * @OA\Property(
     *     title="title_short",
     *     description="The short title of the citation style"
     * )
     * @var string
     */
    private string $title_short;

    /**
     * @OA\Property(
     *     title="style_updated",
     *     description="The last update date of the citation style"
     * )
     * @var string
     */
    private string $style_updated;

    /**
     * @OA\Property(
     *     title="url",
     *     description="The to get the xml content of the citation style"
     * )
     * @var string
     */
    private string $url;

    /**
     * @OA\Property(
     *     title="style_content",
     *     description="The content of the citation style"
     * )
     * @var string
     */
    private string $style_content;


}
