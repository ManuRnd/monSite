<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 24/12/17
 * Time: 16:12
 */

namespace App\Controller;


use App\AppEvent;
use App\Entity\Article;
use App\Event\ArticleEvent;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('Article/show.html.twig', ["articles" => $articles]);
    }


    /**
     * @Route(
     *     path="/new",
     *     name="app_articles_new"
     * )
     */
    public function newAction(Request $request){

        $article= $this->get(Article::class);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article=$form->getData();
            $event = $this->get(ArticleEvent::class);
            $event->setArticle($article);
            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch(AppEvent::ARTICLE_ADD, $event);
            return $this->redirectToRoute("app_article_index");
        }
        return $this->render("Article/add.html.twig", array("form" => $form->createView()));

    }
}