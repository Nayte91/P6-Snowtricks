<?php

namespace App\Form;

use App\Entity\Video;
use App\Service\VideoPlatformParser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    private $parser;

    public function __construct(VideoPlatformParser $parser)
    {
        $this->parser = $parser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, [
                'trim' => true,
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Youtube, DM or Vimeo URL'],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $video = $event->getData();
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
            'data_class' => Video::class,
        ]);
    }
}
