<?php

namespace App\Controller;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route(
     *     path="/",
     *     name="app_home_index"
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(Article::class)->findBy([],["updatedAt" => "desc"]);
        return $this->render('Home/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route(path="/contact",name="app_contact")
     */
    public function showAction(){
        return $this->render('Home/contact.html.twig');
    }

}
