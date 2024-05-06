<?php
namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static Gender MALE()
 * @method static Gender FEMALE()
 * @method static Gender OTHER()
 */
class Role extends Enum
{
    private const ROlE_ADMIN = 'ROlE_ADMIN';
    private const ROlE_USER = 'ROLE_USER';
}
