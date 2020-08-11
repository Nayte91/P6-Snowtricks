<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\Video;
use App\Service\VideoPlatformParser;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                /** @var Figure */
                $figure = $event->getData();
                if (null !== $figureName = $figure->getName()) {
                    $figure->setSlug($this->slugger->slug($figureName)->lower());
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