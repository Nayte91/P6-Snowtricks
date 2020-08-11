<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use App\Service\VideoPlatformParser;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;;

class FigureType extends AbstractType
{
    private $slugger;
    private $parser;

    public function __construct(SluggerInterface $slugger, VideoPlatformParser $parser)
    {
        $this->slugger = $slugger;
        $this->parser = $parser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', CKEditorType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $figure = $event->getData();
                if (null !== $figureName = $figure->getName()) {
                    $figure->setSlug($this->slugger->slug($figureName)->lower());
                } else {
                    $figure->setSlug($figure->getId());
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}