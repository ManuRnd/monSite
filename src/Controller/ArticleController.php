<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 24/12/17
 * Time: 16:12
 */

namespace App\Controller;


use App\AppAccess;
use App\AppEvent;
use App\Entity\Media;
use App\Entity\Article;
use App\Event\ArticleEvent;
use App\Event\MediaEvent;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route(path="/article")
 */
class ArticleController extends Controller
{

    /**
     * @Route(path="/index/{option}",name="app_article_index",defaults={"option":"title"})
     */
    public function indexAction($option){
        $em=$this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)->findBy([],[$option=>"DESC"]);
        return $this->render('Article/index.html.twig', ["articles" => $articles]);
    }


    /**
     * @Route(
     *     path="/new",
     *     name="app_articles_new"
     * )
     */
    public function newAction(Request $request)
    {
        $article = $this->get(\App\Entity\Article::class);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $articleEvent = $this->get(ArticleEvent::class);

            $mediaEvent = $this->get(MediaEvent::class);
            $mediaEvent->setMedia($this->get(Media::class));
            $mediaEvent->setFile($form['media']->getData());

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppEvent::MEDIA_ADD, $mediaEvent);

            $article->setMedia($mediaEvent->getMedia());

            $articleEvent->setArticle($article);

            $dispatcher->dispatch(AppEvent::ARTICLE_ADD, $articleEvent);


            return $this->redirectToRoute("app_home_index");

        }

        return $this->render("Article/add.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/edit", name="app_article_edit")
     *
     */
    public function editAction(Request $request, Article $article, AuthorizationCheckerInterface $authorizationChecker)
    {

        if(false === $authorizationChecker->isGranted(AppAccess::ArticleEdit, $article)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("app_home_index");
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event = $this->get(ArticleEvent::class);
            $event->setArticle($article);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::ARTICLE_EDIT, $event);

            return $this->redirectToRoute("app_home_index");
        }

        return $this->render("Article/edit.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route(path="/{id}/delete", name="app_article_delete")
     *
     */
    public function deleteAction(Article $article, AuthorizationCheckerInterface $authorizationChecker)
    {
        if(false === $authorizationChecker->isGranted(AppAccess::ArticleEdit, $article)){
            $this->addFlash('error', 'access deny !');
            return $this->redirectToRoute("app_home_index");
        }

        $event = $this->get(ArticleEvent::class);
        $event->setArticle($article);
        $dispatcher = $this->get("event_dispatcher");
        $dispatcher->dispatch(AppEvent::ARTICLE_DELETE, $event);

        return $this->redirectToRoute("app_home_index");

    }

    /**
     * @Route(
     *     path="/show/{id}",
     *     name="app_article_show"
     * )
     */
    public function showAction(Article $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->findOneBy(array('id'=>$id));
        $article->setHitcount(($article->getHitcount())+1);
        $em->persist($article);
        $em->flush();
        return $this->render('Article/show.html.twig', ['article' => $article]);
    }
}