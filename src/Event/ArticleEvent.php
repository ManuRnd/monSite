<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 13/12/17
 * Time: 14:28
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{
    protected $article;

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }




}