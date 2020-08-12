<?php


namespace App\Event;

use App\Entity\Figure;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureListener
{
    private $slugger;
    private $manager;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $manager)
    {
        $this->slugger = $slugger;
        $this->manager = $manager;
    }

    /** @ORM\PrePersist */
    public function prePersist(Figure $figure, LifecycleEventArgs $event)
    {
        $figure->setCreatedAt(new \DateTime);
        $this->Slugggify($figure);
    }

    /** @ORM\PreUpdate */
    public function preUpdate(Figure $figure, LifecycleEventArgs $event)
    {
        $this->Slugggify($figure);
    }

    private function Slugggify(Figure $figure)
    {
        if ($figure->getName()) {
            $figure->setSlug($this->slugger->slug($figure->getName())->lower());
        }
    }
}