<?php

namespace App\Virtual\Models;

use App\Enums\ArticleCollaboratorTypes;

/**
 * @OA\Schema(
 *     title="Role",
 *     description="Role model",
 *     @OA\Xml(
 *         name="Role"
 *     )
 * )
 */

class Collaborator
{
    /**
     * @OA\Property(
     *     title="User Id",
     *     description="The ID of the user",
     *     format="uuid",
     * )
     *
     * @var string
     */
    private $user_id;


    /**
     * @OA\Property(
     *     title="Type",
     *     description="The type of the collaborator"
     * )
     *
     * @var ArticleCollaboratorTypes
     */
    private ArticleCollaboratorTypes $type;
}
