<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 24/12/17
 * Time: 16:18
 */

namespace App\DataFixtures\ORM;


use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArticle extends Fixture
{
    const USER_PASSWORD = 'user';
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('manu@user.fr');
        $password = $this->container->get('security.password_encoder')->encodePassword($user, self::USER_PASSWORD);
        $user->setPassword($password);
        $manager->persist($user);

       $article=new Article();
       $article->setTitle("Cryptarithme");
       $article->setContent("Application permettant de résoudre un cryptarithme");
       $article->setUser($user);
       $article->setTechno("Java");
        $manager->persist($article);

        $manager->flush();
    }
}