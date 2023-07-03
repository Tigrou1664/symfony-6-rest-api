<?php

namespace App\DBAL\Types;

/**
 * The RoleType class defines the allowed Role types in a shop:
 * - 'vendor' for a vendor user in shop
 * - 'admin' for an admin user in shop
 */
class RoleType extends BaseEnumType
{
    public const ROLE_VENDOR = 'vendor';
    public const ROLE_ADMIN = 'admin';

    public const ROLE_DEFAULT = self::ROLE_VENDOR;

    protected string $name = 'enum_role_type';

    protected array $values = [
        self::ROLE_VENDOR,
        self::ROLE_ADMIN,
    ];
}