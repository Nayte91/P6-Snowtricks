<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\Video;
use App\Service\VideoPlatformParser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

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
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
            ])
            ->add('url', UrlType::class, [
                'trim' => true,
                'mapped' => false,
                'required' => false,
                'label' => 'Ajouter une nouvelle vidéo',
                'attr' => ['placeholder' => 'Youtube, Dailymotion ou Vimeo'],
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Figure */
                $figure = $event->getData();
                if (null !== $figureName = $figure->getName()) {
                    $figure->setSlug($this->slugger->slug($figureName)->lower());
                }
            })
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $video = new Video;
                $userUrl = $event->getForm()->get('url')->getNormData();
                if (null !== $userUrl) {
                    $this->parser->parseUrl($userUrl);
                    $video->setVideoId($this->parser->getVideoId());
                    $video->setPlatform($this->parser->getWebSite());
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