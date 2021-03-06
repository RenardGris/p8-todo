<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\user;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client;

    private $objectManager;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->objectManager = static::$kernel->getContainer()->get('doctrine')->getManager();

    }

    //check if /user request return http code is equal to 200
    public function testListAction()
    {
        self::logAsAdmin();
        $this->client->request('GET', '/users');
        static::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );
    }

    public function testListActionAsUser()
    {
        self::logAsUser();
        $this->client->request('GET', '/users');
        static::assertEquals(403, $this->client->getResponse()->getStatusCode() );
    }

    //Insert new user and check if alert contain validation text
    public function testCreateAction()
    {
        self::logAsAdmin();
        $crawler = $this->client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $randomizer = round(rand(1,100));
        $this->client->submit($form, [
            "user[username]" => "testUser" . $randomizer,
            "user[password][first]" => "demo",
            "user[password][second]" => "demo",
            "user[email]" => "testUser".$randomizer."@gmail.com",
            "user[roles]" => "ROLE_USER",
        ]);
        $crawler =  $this->client->followRedirect();
        $this->assertContains( "L'utilisateur a bien été ajouté.", $crawler->filter('div.alert.alert-success')->text());
    }

    //update user and check if alert contain validation text
    public function testEditAction()
    {
        self::logAsAdmin();
        $user = self::getUser();
        $crawler = $this->client->request('GET', '/users/'. $user->getId() .'/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $randomizer = round(rand(101,1000));
        $this->client->submit($form, [
            "user[username]" => "testEditUser" . $randomizer,
            "user[password][first]" => "demo1",
            "user[password][second]" => "demo1",
            "user[email]" => "user1@email.com"
        ]);
        //$this->client->submit($form);
        $crawler =  $this->client->followRedirect();
        $this->assertContains( "L'utilisateur a bien été modifié", $crawler->filter('div.alert.alert-success')->text());
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

    public function getUser()
    {
        return $this->objectManager->getRepository(User::class)->findOneBy(['email'=>'user1@email.com']);
    }

}