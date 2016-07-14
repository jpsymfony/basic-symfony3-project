<?php

namespace App\FormationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\FormationBundle\Entity\User;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $userDatas = [
            'user1' => [
                'email' => 'user1@yoyo.com',
                'password' => 'password',
            ],
            'user2' => [
                'email' => 'user2@yoyo.com',
                'password' => 'password',
            ],
            'user3' => [
                'email' => 'user3@yoyo.com',
                'password' => 'password',
            ],
        ];

        foreach ($userDatas as $userName => $userData) {
            $user = new User();
            $user->setUsername($userName);
            $user->setEmail($userData['email']);
            $user->setEnabled(true);
            $user->setPlainPassword($userData['password']);
            $userManager->updatePassword($user);
            $manager->persist($user);
            $this->addReference(sprintf('user-%s', $userName), $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
