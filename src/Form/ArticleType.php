<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 24/12/17
 * Time: 16:36
 */

namespace App\Form;


use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleType extends AbstractType
{
    protected $token;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->token = $storage;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Article::class,
                ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("title", TextType::class)
            ->add("content", TextType::class)
            ->add("techno", TextType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])->getForm();
    }

    public function onPreSetData(FormEvent $formEvent)
    {
        $form = $formEvent->getForm();
        $article = $formEvent->getData();

        if($article->getId() === null){
            $article->setUser($this->token->getToken()->getUser());
            $form->add("save", SubmitType::class, ["label" => "Créer"]);
        } else{
            $form->add("save", SubmitType::class, ["label" => "Modifier"]);
        }
    }
}