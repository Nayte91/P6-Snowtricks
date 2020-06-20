<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
        $this->loadFigures($manager);
        $this->loadUsers($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        foreach ($this->getCategoryData() as $index => $name) {
            $category = new Category;
            $category->setName($name);

            $manager->persist($category);
        }

        $manager->flush();
    }

    private function loadFigures(ObjectManager $manager)
    {
        foreach ($this->getFiguresData() as $figureData) {
            $figure = new Figure;
            $category = $manager->getRepository(Category::class)->findOneByName($figureData['category']);
            $figure->setName($figureData['name'])->setSlug($figureData['slug'])->setCreatedAt(new \DateTime($figureData['createdAt']))->setDescription($figureData['description'])->setCategory($category);
            $manager->persist($figure);
        }

        $manager->flush();
    }

    /* Actually, 1 user */
    protected function loadUsers(ObjectManager $manager)
    {
        $user = new User;
        $user->setUsername('usertest');
        $user->setEmail('user.test@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'azerty'));

        $manager->persist($user);
        $manager->flush();
    }

    private function getCategoryData(): array
    {
        return [
            'Straight airs',
            'Grabs',
            'Spins',
            'Flips',
            'Inverted hand plants',
            'Slides',
            'Stalls',
            'Tweaks',
            'Miscellaneous',
        ];
    }

    private function getFiguresData(): array
    {
        return [
            [
                'name' =>'Ollie',
                'slug' => 'ollie',
                'description' => 'Un saut tout simple.',
                'category' => 'Straight airs',
                'createdAt' => '20-12-1982 17:20:00',
                ],
            [
                'name' => 'Nosegrab',
                'slug' => 'nosegrab',
                'description' => 'attraper la pointe avant de son snowbard avec une de ses mains.',
                'category' => 'Grabs',
                'createdAt' => '11-09-2001 09:40:50',
            ]

        ];
    }
}