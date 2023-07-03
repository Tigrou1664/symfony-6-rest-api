<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\DBAL\Types\UserType;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class UserTest extends TestCase
{
    public function testConstruct()
    {
        $user = new User();
        //$this->assertInstanceOf(UuidV4::class, $user->getId());
        $this->assertIsString($user->getId());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals(null, $user->getPhone());
        $this->assertEquals(null, $user->getMobile());
        $this->assertEquals(null, $user->getFax());

        $this->assertEquals('fr', $user->getLanguage());

        $this->assertEquals(UserType::USER_DEFAULT, $user->getEprType());

        $this->assertEquals(false, $user->getEprExpert());
        $this->assertEquals(false, $user->getEmailVerified());

        $this->assertEquals("", $user->getUiPreferences());
    }

    public function testGettersSetters()
    {
        $user = new User();

        $user->setEmail('A');
        $this->assertEquals('A', $user->getEmail());

        $user->setRoles(['A', 'B']);
        $this->assertEquals(['A', 'B', 'ROLE_USER'], $user->getRoles());
    }
}
