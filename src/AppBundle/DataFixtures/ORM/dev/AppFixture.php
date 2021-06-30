<?php
namespace AppBundle\DataFixtures\ORM\dev;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixture implements FixtureInterface
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
                "username" => "user2",
                "email" => "user2@email.com",
                "role" => "ROLE_USER",
            ],
            [
                "username" => "user3",
                "email" => "user3@email.com",
                "role" => "ROLE_USER",
            ],
            [
                "username" => "user4",
                "email" => "user4@email.com",
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
        unset($userData);
        $manager->flush();
    }

    public function loadTasks(ObjectManager $manager)
    {

        for ($i = 0; $i < 3; $i++) {
            $task = new Task();
            $task->setTitle("Anonymous Task Title N° " . $i);
            $task->setContent("Content for Anonymous task n° " . $i);
            $task->setCreatedAt(new \DateTime());
            $manager->persist($task);
        }

        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user){
            for ($i = 0; $i < 3; $i++) {
                $task = new Task();
                $task->setTitle("Fake Task Title N° " . $user->getId() .'-'. $i);
                $task->setContent("Content for Fake task n° " . $user->getId() .'-'. $i);
                $task->setCreatedAt(new \DateTime());
                $task->setUser($user);
                $manager->persist($task);
            }
        }
        $manager->flush();
    }

}