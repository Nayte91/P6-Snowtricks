<?php

namespace App\Fixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Video;
use App\Service\VideoPlatformParser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $slugger;
    private $parser;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger, VideoPlatformParser $parser)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
        $this->parser = $parser;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
        $this->loadFigures($manager);
        $this->loadVideos($manager);
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
            $figure
                ->setName($figureData['name'])
                ->setDescription($figureData['description'])
                ->setCategory($category)
                ->setLastModified(new \DateTime);
            $manager->persist($figure);
        }

        $manager->flush();
    }

    private function loadVideos(ObjectManager $manager)
    {
        foreach ($this->getFiguresData() as $figureData) {
            if (array_key_exists('videos', $figureData)) {
                $figure = $manager->getRepository(Figure::class)->findOneByName($figureData['name']);
                foreach ($figureData['videos'] as $videoData) {
                    $video = new Video;
                    $this->parser->parseUrl($videoData);
                    if ($this->parser->hasParsedRight()) {
                        $video->setVideoId($this->parser->getVideoId());
                        $video->setPlatform($this->parser->getWebSite());
                        $figure->addVideo($video);

                        $manager->persist($video);
                    }
                }
            }
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
                'description' => "
<p>Les Ollies sont l'une des fugures les plus essentielles à apprendre en matière de snowboard. Que vous fassiez des sauts de parc, des coups latéraux, des jibs urbains ou du freeride, l'ollie est le moyen le plus efficace de prendre l'air.</p>

<p>Les Ollies sont essentiels car ils utilisent l'énergie du flex de votre planche pour vous faire monter. Essayez de sauter sur un terrain plat ... vous n'êtes pas devenu très haut, n'est-ce pas? Maintenant, laissez-nous vous apprendre à Ollie et obtenez de la hauteur dans ce saut!</p>

<h4>Plus de flexibilité, plus de hauteur !</h4>

<p>Poussez votre planche vers l'avant, glissez-la sous vous et équilibrez vos hanches au-dessus de la queue de votre planche. Dans cette position, votre planche se pliera et se soulèvera du sol dans une position de presse de queue. Utilisez cette presse pour sortir, de votre queue et dans les airs, en atterrissant uniformément sur les deux pieds.</p>

<p>Commencez à ajouter du poids à votre pied avant, pour vous aider à placer votre planche sous vous. Cela vous aide à obtenir plus de flexibilité et plus de hauteur hors de votre Ollie. Bien qu'il soit bon d'avoir une idée du mouvement Ollie sur le plat, la plupart des snowboarders trouvent plus facile d'effectuer un Ollie en mouvement et de sauter pendant un ride.</p>

<h4>Une question de timing</h4>

<p>Défiez le timing de vos Ollies en utilisant quelque chose comme une boule de neige, un gant ou même un bâton comme marqueur de position au sol pour sauter et chronométrer votre pop. Quand il s'agit de faire des sauts en snowpark de parc, le timing compte beaucoup, donc cet exercice vous aidera vraiment à composer votre timing!</p>
                ",
                'category' => 'Straight airs',
                'pictures' => []
            ],
            [
                'name' => 'Nosegrab',
                'description' => 'attraper la pointe avant de son snowbard avec une de ses mains.',
                'category' => 'Grabs',
                'pictures' => []
            ],
            [
                'name' => 'Melon',
                'description' => "
<h4>L’approche</h4>

<p>Commencez directement derrière le kicker à un point qui vous permettra d'atterrir en toute sécurité sur le dessus de la table ou juste au-dessus de l'articulation. Recréez une forme d'entonnoir avec vos virages en vous concentrant sur la conduite droite au centre du kicker.</p>

<h4>Décollage</h4>

<p>Visez une base plate lorsque vous remontez le kicker avec le haut du corps aligné avec la planche. Vous avez la possibilité de maintenir cette base plate ou de transférer légèrement la pression sur vos orteils lorsque vous lancez un pop ou un ollie. Un ollie flotte souvent plus facilement dans la benne qu'une pop. Vous pouvez monter le kicker légèrement plus bas que la normale pour réduire la distance de déplacement pour la benne.</p>

<h4>Trick</h4>

<p>Saisissez le bord du talon entre les fixations avec votre main avant en gardant la planche à plat.</p>

<h4>Landing</h4>

<p>Soyez prêt à absorber l'atterrissage en vous concentrant vers un atterrissage en douceur sur le bord des orteils pour permettre le contrôle, puis amincissez la planche à plat si nécessaire en absorbant plus à travers vos jambes que votre dos. Roulez tout droit pendant un court instant avant de vérifier la vitesse pour créer un air droit plus fluide</p>

<p>Points à vérifier</p>

<ul>
    <li>Préparez-vous à saisir en montant légèrement plus bas que la normale</li>
	<li>Faites un Pop ou un ollie pour bien mettre la planche en place</li>
</ul>
                ",
                'category' => 'Grabs',
                'pictures' => []
            ],
            [
                'name' =>'Cork',
                'description' => "
<p>Un cork est une rotation verticale mais aussi horizontale plus ou moins désaxée, selon un mouvement d'épaules effectué juste au moment du saut.</p>

<p>Fondamentalement, c'est un flip hors axe lancé en arrière avec un spin (le plus souvent 540º ou Rodeo 5). Tire-bouchon ou «Cork»: Le skieur effectue une rotation hors axe ou inversée horizontale distincte. À aucun moment, les pieds du skieur ne doivent être au-dessus de leur tête. Double Cork ou «Dub Cork»: Le skieur effectue deux rotations hors axe distinctes.</p>
                ",
                'category' => 'Flips',
                'pictures' => []
            ],
            [
                'name' => 'Frontside 180',
                'category' => 'Spins',
                'description' => "
<p>Le Frontside 180 est une des rotations la plus facile à apprendre sur un snowboard. Pour bien le réaliser il y a une astuce devenue intemporelle qui, lorsqu'elle est exécutée avec le bon style, résiste bien aux triplés le plus noueux.</p>

<h4>Par où commencer ?</h4>

<p>En roulant sur une pente douce, commencez par effectuer des 180 sur le sol. Engagez votre talon avec la neige, amorcez votre virage sur le talon et continuez à tourner jusqu'à ce que vous rouliez dans le sens opposé sur la pente. Vous roulez maintenant dans une position de changement.</p>

<p>Pouvoir rouler en switch est crucial pour les 180 et les tonnes d'autres figures de freestyle, alors soyez à l'aise avec !</p>

<p>De là, revenez simplement à votre position de conduite habituelle en utilisant votre talon. Vous venez de terminer vos premiers Frontside 180 et Switch Frontside 180</p>

<h4>Pourquoi est-il appelé Frontside?</h4>

<p>C'est ce qu'on appelle une rotation avant car la face avant de votre corps est orientée vers le bas tout au long des 180 degrés de rotation.</p>

<h4>Ajouter une rotation</h4>

<p>Pendant que vous faites glisser le snow sur la neige, ajoutez des mouvements de rotation avec le haut de votre corps. Balancer vos bras et vos épaules dans le virage est la clé pour créer l'élan requis pour le 180.</p>

<p>Dans le même temps, essayez également de retirer votre poids de la planche en étendant légèrement vos genoux, mais gardez la planche en contact avec la neige. Cette technique vous permet d'être à l'aise avec la sensation de sauter un Front 1, sans réellement sauter un Front 1.</p>

<h4>Frontside 180's On Flats</h4>

<p>Si vous essayez de sauter un Frontside 180 à partir d'une base plate, vous sentirez votre planche glisser. Utilisez plutôt vos carres pour saisir la neige. Créez un léger bord d'orteil, remontez pour créer l'élan et sautez de votre bord d'orteil pour faire tourner le Frontside 180.</p>

<h4>Frontside 180's en traversant</h4>

<p>Parcourez une pente sur le bord de votre orteil pour faire votre premier Frontside 180.</p>

<p>Créez une adhérence avec la neige en utilisant le bord de vos orteils</p>

<p>Remontez le haut de votre corps à l'aide des bras et des épaules</p>

<p>Lorsque vous sautez du bord de vos orteils, balancez votre bras arrière sur votre corps pour qu'il pointe dans la nouvelle direction de déplacement</p>

<p>Essayez maintenant de chronométrer ces mouvements avec une bosse dans la neige pour obtenir une hauteur supplémentaire. Tout en traversant sur le bord de votre talon, essayez quelques Frontside 180 en utilisant un Ollie puissant. Vous devez Ollie haut car c'est un moyen plus difficile de faire tourner le Frontside 180.</p>

<h4>L'importance du&nbsp;pop</h4>

<p>Au fur et à mesure que vous vous améliorez avec vos Front 1, il est important d'ajouter de la pop en étendant vos jambes lorsque vous quittez le décollage. Plus vous obtenez de hauteur, vous aurez besoin de moins de rotation tout au long de votre spin. Vos 180 se déplaceront plus facilement et les rendront super flottants.</p>",
                'pictures' => []
            ],
            [
                'name' =>'Canadian Bacon',
                'description' => "
<h4>Le grab</h4>

<p>Cette prise vous oblige à passer par derrière pour&nbsp;saisir la planche entre les jambes. Ne vous focalisez pas&nbsp;trop sur cette dernière phrase. Le bacon canadien n'est pas aussi compliqué qu'il y paraît - même s'il semble quand même bizarre au début !&nbsp;</p>

<h4>La torsion</h4>

<p>Ce trick intrigue et en général les spectateurs y&nbsp;regardent à&nbsp;deux fois. Il faut un peu de temps pour comprendre comment atteindre la prise.&nbsp;Bien que ce trick&nbsp;ne soit jamais devenu&nbsp;courant, lorsqu'il&nbsp;est ramené&nbsp;avec une manœuvre frontale comme Tim Eddy, le Canadian Bacon&nbsp;peut aider à&nbsp;se sentir vraiment cool.</p>

<h4>Un peu d'histoire</h4>

<p>Inventée en 1988/89, la légende du snowboard Jason Ford se souvient que <em>Brushie a commencé à faire celle-ci en premier</em>. Brushie n’en est pas trop sûr. <em>Je ne pense pas avoir commencé le bacon canadien</em>, dit-il. «<em>[Mais] j'aurais pu tout simplement me défoncer. La plupart de ces saisies ont eu lieu en été sur les trampolines. Je ne me souciais pas vraiment des saisies entre les jambes en général. Ce n'était vraiment qu'une phase rapide pour moi tout en essayant de penser à de nouvelles astuces. Étant donné que vos pieds ne se détachaient pas de la planche et que nous ne tournions pas comme des fous, différentes prises étaient la seule chose à proposer. Je n'ai pas fait ces prises depuis très longtemps. J'ai vraiment essayé de rester fidèle au Snwoboard, et pour moi, c'était la mauvaise direction</em>. »</p>",
                'category' => 'Grabs',
                'pictures' => [
                    [
                        'url' => 'https://s1.dmcdn.net/v/N3Y8S1S_-HXmQeoaK/x1080',
                        'extension' => 'jpeg',
                        'alt' => 'Eddy'
                    ],
                ],
                'videos' => [
                    "https://www.dailymotion.com/video/x6eaiho",
                    "https://www.youtube.com/watch?v=IVUSdEBRZ0Q",
                ]
            ],
            [
                'name' => 'Frontside 50-50',
                'description' => "
<p>Le 50-50 est probablement le plus simple de tous les tricks et le premier que vous apprendrez. Son nom signifie littéralement : glisser sur une rampe ou un rail. En soi, ce n'est pas le mouvement le plus excitant du monde, mais lorsque vous le liez dans un combo, les choses deviennent plus intéressantes. Ses trois variantes sont idéales pour les rampes plates que vous trouverez dans la plupart des parcs et des dômes de neige, alors pourquoi ne pas donner une chance aux 50-50 ?</p>

<h4>L'approche</h4>

<p>Tout d'abord, vous devez trouver un bon rail plat ou une rampe large avec laquelle vous êtes à l'aise, faire quelques descente en faisant des 50-50 simples pour trouver la vitesse idéale dont vous aurez besoin pour l'attaquer. Lorsque vous êtes prêt à essayer quelque chose de nouveau, approchez-vous du rail (attention à ne pas aller trop vite, sinon vous finirez par décoller l'extrémité de la partie plate et manquerez la section inférieure, ce qui vous fera inévitablement atterrir sur les fesses). Votre poids doit être centré sur la planche - restez détendu avec les genoux légèrement pliés et gardez les yeux droit devant.</p>

<h4>Le pop</h4>

<p>Certains rails / rampes sont simplement fait pour y monter, mais la plupart du temps, vous aurez envie de vous amusez un peu plus avec. Sautez de votre pied arrière en utilisant le flex de la queue de votre planche pour vous aider à sauter. Le kicker fera une grande partie du travail, alors allez y mollo ou vous pourriez finir par manquer la rampe. Essayez d'atterrir parfaitement aligné avec la rampe et restez centré sur votre planche avec les genoux pliés. Faites attention de ne pas virer de bord - gardez votre base à plat.</p>

<h4>Positionnement</h4>

<p>Gardez votre poids centré tout au long du 50/50, mais lorsque vous approchez de la partie inférieure de la rampe, déplacez légèrement votre poids vers le pied avant et placez votre pied arrière devant vous. Encore une fois, assurez-vous de garder la planche à plat pour ne pas glisser sur le bord.</p>

<h4>Style</h4>

<p>Une fois que vous avez atteint la position de glissement de la planche, ajustez votre pied arrière sur le côté tout en gardant vos épaules alignées avec la rampe. Vos yeux doivent rester concentrés sur la fin de l'obstacle. Cela vous aidera lorsque vous arriverez au bout du rail et que vous devrez ramener votre planche à terre.</p>

<h4>Atterrissage</h4>

<p>La partie la plus délicate. Restez concentré et confiant, prêt à vous lancer. Lorsque vous approchez de l'extrémité du rail, donnez-lui un peu de pop et rapprochez un peu vos genoux pour absorber l'impact. Vous devriez atterrir avec la planche à plat mais légèrement sur le bord afin de garder le contrôle.</p>",
                'category' => 'Slides',
            ],
            [
                'name' => 'Japan Air',
                'description' => "
<p>Un Japan Air est super divertissant à regarder et encore plus amusant à piétiner. Ajouter du Japon à vos tours est un moyen sûr d'obtenir des accessoires de vos amis.</p>

<h4>Trois billets pour Tokyo</h4>

<ol>
    <li>Prenez un Mute et lancez-vous en avant en jetant votre autre bras en arrière</li>
	<li>Gardez votre bras à l'extérieur de votre genou, en dehors du genou mais à l'intérieur des fixations.</li>
	<li>Pliez les genoux et cambrez le dos.</li>
</ol>

<p>Ce trick necessiterait de prendre quelques cours de yoga&nbsp;</p>

<p>Ce trick est un peu plus avancé, vous devriez donc avoir de l'expérience avec la plupart des grabs de base avant de prendre votre envol.</p>
                ",
                'category' => 'Grabs',
            ],
            [
                'name' => 'Backside Rodeo 540 Melon',
                'description' => "
<p>Rotation désaxée de 3 tours effectuée tête en bas. Rotation mélangée à un backflip avec une implusion sur les talons.</p>
                                
<h4>1. La préparation</h4>

<p>Cela pourrait être une astuce plus difficile, mais vous devriez prendre la course et la transition exactement de la même manière que les 180 et 360, en mettant en place votre poids centré, votre planche à plat et un peu de pression sur le bord de vos orteils.</p>

<h4>2. Le pop</h4>

<p>Maintenant pour la partie amusante! Lorsque vous atteignez la lèvre, vous voulez faire éclater comme vous le feriez pour un 540 arrière afin que vous soyez sûr d'obtenir suffisamment de rotation pour vous déplacer. Pendant l'éclatement, cependant, vous devrez jeter votre tête sur votre épaule arrière afin de lancer l'élément hors axe du tour.</p>

<h4>3. Le grab</h4>

<p>Vous avez vraiment besoin de vous présenter le tableau si vous voulez saisir cette astuce. Si vous deviez même essayer de tendre la main, vous êtes dans un monde en difficulté et vous atterrirez très probablement sur votre cou! Pas beaucoup de plaisir et un bon tracas pour la patrouille de ski vous gratter de la pente. Dans cette photo, je prends du melon - je trouve que c'est la prise la plus facile pour aider à faire tourner la rotation - mais encore une fois, si vous trouvez d'autres prises plus faciles avec des backflips droits et 540, essayez-les.</p>

<h4>4. Continuez à saisir</h4>

<p>Continuez à tenir la benne aussi longtemps que vous le pouvez, mais sur cette astuce, je dirais que c'est encore plus important de le faire, car l'ouverture arrêtera la rotation et vous risquez bien de la tourner, ce qui ne sera pas joli.</p>

<h4>5. L’atterissage</h4>

<p>Parce que vous venez sur un axe étrange, vous pouvez voir votre atterrissage très loin, ce qui aide. La seule partie difficile est que vous atterrissez aveugle comme vous le feriez sur un arrière 180 ou 540, donc les mêmes règles d'atterrissage s'appliquent: regardez entre vos fixations au sol, repérez votre atterrissage et plantez les deux pieds en même temps –Avec un peu de pression sur le bord des orteils pour arrêter la rotation. Il est trop facile de revenir immédiatement si vous ne faites pas attention et que la pression sur le bord des orteils est la clé pour l'arrêter. Après vous être assuré que vous avez fait tout ce que vous devriez être en mesure de désactiver l'interrupteur, juste à temps pour le dernier appel!</p>
                ",
                'category' => 'Spins',
            ],
        ];
    }
}