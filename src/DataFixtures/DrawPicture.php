<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Draw;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DrawPicture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $hash = $this->encoder->hashPassword(new User(), 'test');

        for ($u = 0; $u < 5; $u++) {
            $user = (new User())
                ->setEmail("test$u@test.com")
                ->setPassword($hash)
                ->setIsNewsletter(false);
            $manager->persist($user);
            $manager->flush();
        }

        $users = $manager->getRepository(User::class)->findAll();

        for ($j = 0; $j < 5; $j++) {
            $category = (new Category())
                ->setName("Categorie $j");
            $manager->persist($category);

            for ($i = 0; $i < random_int(1, 10); $i++) {
                $draw = (new Draw())
                    ->setName("Mon titre $i")
                    ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat")
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setLikes(random_int(0, 40))
                    ->setPath("https://picsum.photos/200")
                    ->addCategory($category);
                $manager->persist($draw);

                for ($h = 0; $h < random_int(0, 25); $h++) {
                    $comment = (new Comment())
                        ->setComment('blablabla')
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUserComment($users[random_int(0, 2)])
                        ->setDraw($draw);
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
