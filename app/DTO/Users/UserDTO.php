<?php
namespace App\DTO\Users;

use Spatie\DataTransferObject\DataTransferObject;

class UserDTO extends DataTransferObject
{
    public string $id;

    public string $name;

    public string $email;

    public static function fromArray(array $data): UserDTO
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            email: $data['email']
        );
    }
}
