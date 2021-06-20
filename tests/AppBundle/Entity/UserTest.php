<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;
    private $task;

    public function setUp()
    {
        $this->user = new User();
        $this->user->setRoles('ROLE_USER');
        $this->task = new Task();
        $this->task->setTitle("test");
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

    public function testAddTask()
    {
        $this->user->addTask($this->task);
        $tasks = $this->user->getTasks();
        $this->assertSame("test", $tasks[0]->getTitle());
    }

    public function testTasks()
    {
        $this->user->removeTask($this->task);
        $this->assertEmpty($this->user->getTasks());
    }

}