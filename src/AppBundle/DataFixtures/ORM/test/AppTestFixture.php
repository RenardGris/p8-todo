<?php
namespace AppBundle\DataFixtures\ORM\test;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AppTestFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        self::loadUsers($manager);
        self::loadTasks($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        $users = [
            [
                "username" => "admin",
                "email" => "admin@email.com",
                "role" => "ROLE_ADMIN",
            ],
            [
                "username" => "user",
                "email" => "user@email.com",
                "role" => "ROLE_USER",
            ],
            [
                "username" => "user1",
                "email" => "user1@email.com",
                "role" => "ROLE_USER",
            ]
        ];
        foreach ($users as $userData){
            $user = new User();
            $user->setUsername($userData["username"]);
            $user->setEmail($userData['email']);
            $user->setPassword(password_hash("demo", PASSWORD_DEFAULT ));
            $user->setRoles($userData["role"]);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function loadTasks(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user){
            for ($j = 0; $j < 5; $j++) {
                $task = new Task();
                $task->setTitle("Test Task Title N° " . $user->getId() .'-'. $j);
                $task->setContent("Content for test task n° " . $user->getId() .'-'. $j);
                $task->setCreatedAt(new \DateTime());
                $task->setUser($user);
                $manager->persist($task);
            }
        }

        for ($i = 0; $i < 3; $i++) {
            $task = new Task();
            $task->setTitle("Anonymous Task Title N° " . $i);
            $task->setContent("Content for Anonymous task n° " . $i);
            $task->setCreatedAt(new \DateTime());
            $manager->persist($task);
        }

        $manager->flush();

    }

}