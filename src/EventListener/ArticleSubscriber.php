<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 13/12/17
 * Time: 15:17
 */

namespace App\EventListener;


use App\AppEvent;
use App\Entity\Article;
use App\Event\ArticleEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleSubscriber implements EventSubscriberInterface
{

    private $em;

    /**
     * UserSubscriber constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(ArticleEvent $articleEvent){
        $entiti=$this->em->getRepository(Article::class)->findAll();
        $this->persist($articleEvent);
    }

    public function persist(ArticleEvent $articleEvent)
    {
        $this->em->persist($articleEvent->getArticle());
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::ARTICLE_ADD => array('add', 0),
        ];
    }
}