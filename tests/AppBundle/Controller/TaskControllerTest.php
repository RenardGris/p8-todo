<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    private $task;

    private $client;

    private $objectManager;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->objectManager = static::$kernel->getContainer()->get('doctrine')->getManager();
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


}