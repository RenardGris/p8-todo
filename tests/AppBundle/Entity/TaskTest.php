<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase
{
    private $task;

    public function setUp()
    {
        $this->task = new Task();
    }

    public function testId()
    {
        $this->assertNull($this->task->getId());
    }

    public function testCreatedAt()
    {
        $date = new \DateTime();
        $this->task->setCreatedAt($date);
        $this->assertSame($date, $this->task->getCreatedAt());
    }

    public function testTitle()
    {
        $this->task->setTitle('Test Task Title');
        $this->assertSame('Test Task Title', $this->task->getTitle());
    }

    public function testContent()
    {
        $this->task->setContent('Test Task Content');
        $this->assertSame('Test Task Content', $this->task->getContent());
    }

    public function testIsDone()
    {
        $this->task->toggle(true);
        $this->assertSame(true, $this->task->isDone());
    }

}