<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{

    private $client;

    private $objectManager;

    private $task;

    private $userTask;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->objectManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->task = self::getInsertTask();
        $this->userTask = self::getInsertTaskFromUser();
    }

    //check if /task request return http code is equal to 200
    public function testListAction()
    {
        self::logAsAdmin();
        $this->client->request('GET', '/tasks');
        static::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );
    }

    //Insert new task and check if alert contain validation text
    public function testCreateAction()
    {
        self::logAsAdmin();
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, [
            "task[title]" => "Tache de test",
            "task[content]" => "Tache enregistrée lors d'un test fonctionnel"
        ]);
        $this->client->submit($form);
        $crawler =  $this->client->followRedirect();
        $this->assertContains( "La tâche a été bien été ajoutée.", $crawler->filter('div.alert.alert-success')->text());
    }

    //update task and check if alert contain validation text
    public function testEditTaskAction()
    {
        self::logAsAdmin();
        $crawler = $this->client->request('GET', '/tasks/4/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, [
            "task[title]" => "Tache de test editée",
            "task[content]" => "Tache editée lors d'un test fonctionnel"
        ]);
        //$this->client->submit($form);
        $crawler =  $this->client->followRedirect();
        $this->assertContains( "La tâche a bien été modifié", $crawler->filter('div.alert.alert-success')->text());
    }

    //delete task and check if alert contain validation text
    public function testDeleteTaskAction()
    {
        self::logAsAdmin();
        $this->client->request('GET', '/tasks/43/delete');
        $crawler =  $this->client->followRedirect();
        $this->assertContains( "La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    }

    public function testToggleTaskAction()
    {
        self::logAsAdmin();
        $this->client->request('GET', '/tasks/45/toggle');
        $crawler =  $this->client->followRedirect();
        $this->assertContains(" a bien été marquée comme faite.", $crawler->filter('div.alert.alert-success')->text());
    }

    //Needed for auth
    public function logAsAdmin ()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, [
            '_username' => 'admin',
            '_password' => 'demo'
        ]);
    }

    public function logAsUser ()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, [
            '_username' => 'user',
            '_password' => 'demo'
        ]);
    }


    //In case we want the task insert by the admin during the dunctinal
    public function getInsertTask()
    {
        return $this->objectManager->getRepository(Task::class)->findOneBy(['title'=>'Tache de test']);
    }

    //In case we want the task insert by the user during the functional test
    public function getInsertTaskFromUser()
    {
        return $this->objectManager->getRepository(Task::class)->findOneBy(['title'=>'Tache de test User']);
    }

    //In Case we want a random task
    public function getRandomTask()
    {
        $tasks = $this->objectManager->getRepository(Task::class)->findAll();
        return $tasks[array_rand($tasks)];
    }

    //In Case we want a random task from Admin
    public function getAdminTask()
    {
        $user = $this->objectManager->getRepository(User::class)->findOneBy(['email'=>'admin@email.com']);
        return $this->objectManager->getRepository(Task::class)->findOneBy(['user'=>$user->getId()]);
    }

    //In Case we want a random task from User
    public function getUserTask()
    {
        $user = $this->objectManager->getRepository(User::class)->findOneBy(['email'=>'user@email.com']);
        return $this->objectManager->getRepository(Task::class)->findOneBy(['user'=>$user->getId()]);
    }

    public function getAnonTask()
    {
        return $this->objectManager->getRepository(Task::class)->findOneBy(['user'=>null]);
    }

}