<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testConstruct()
    {
        $user = new Utilisateur();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testGettersSetters()
    {
        $user = new Utilisateur();

        $user->setLogin('admin');
        $this->assertEquals('admin', $user->getLogin());

        $user->setPlainPassword('password');
        $this->assertEquals('password', $user->getPlainPassword());

        $user->setDisplayName('Admin');
        $this->assertEquals('Admin', $user->getDisplayName());

        $user->setRoles(['A', 'B']);
        $this->assertEquals(['A', 'B', 'ROLE_USER'], $user->getRoles());
    }
}
