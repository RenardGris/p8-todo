<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testId()
    {
        $this->assertNull($this->user->getId());
    }

    public function testSalt()
    {
        $this->assertNull($this->user->getSalt());
    }

    public function testRole()
    {
        $this->assertContains("ROLE_USER", $this->user->getRoles());
    }

    public function testUsername()
    {
        $this->user->setUsername("Username");
        $this->assertSame("Username", $this->user->getUsername());
    }

    public function testEmail()
    {
        $this->user->setEmail('test@mail.com');
        $this->assertSame('test@mail.com', $this->user->getEmail());
    }

    public function testPassword()
    {
        $this->user->setPassword('DumbPassword');
        $this->assertSame('DumbPassword', $this->user->getPassword());
    }

}