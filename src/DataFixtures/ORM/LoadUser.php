<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUser extends Fixture
{
    const USER_PASSWORD = 'user';

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@user.fr');
        $password = $this->container->get('security.password_encoder')->encodePassword($user, self::USER_PASSWORD);
        $user->setPassword($password);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('user1@user.fr');
        $password = $this->container->get('security.password_encoder')->encodePassword($user, self::USER_PASSWORD);
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user2@user.fr');
        $password = $this->container->get('security.password_encoder')->encodePassword($user, self::USER_PASSWORD);
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
