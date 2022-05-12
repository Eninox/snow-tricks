<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public CONST TRICK1_REFERENCE = 'trick-trick1';
    public CONST TRICK2_REFERENCE = 'trick-trick2';
    public CONST TRICK3_REFERENCE = 'trick-trick3';
    public CONST TRICK4_REFERENCE = 'trick-trick4';
    public CONST TRICK5_REFERENCE = 'trick-trick5';
    public CONST TRICK6_REFERENCE = 'trick-trick6';
    public CONST TRICK7_REFERENCE = 'trick-trick7';
    public CONST TRICK8_REFERENCE = 'trick-trick8';
    public CONST TRICK9_REFERENCE = 'trick-trick9';

    public function load(ObjectManager $manager): void
    {
         $trick1 = new Trick();
         $trick1->setName('Big Air');
         $trick1->setDescription('Description de la figure Big Air');
         $trick1->setMainPicture('trick7.jpg');
         $trick1->setCategory($this->getReference(CategoryFixtures::FLIP_REFERENCE));
         $trick1->setUserCreator($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($trick1);
         $this->addReference(self::TRICK1_REFERENCE, $trick1);

         $trick2 = new Trick();
         $trick2->setName('Frontside');
         $trick2->setDescription('Description de la figure Frontside');
         $trick2->setMainPicture('trick8.jpg');
         $trick2->setCategory($this->getReference(CategoryFixtures::ROTATION_REFERENCE));
         $trick2->setUserCreator($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($trick2);
         $this->addReference(self::TRICK2_REFERENCE, $trick2);

         $trick3 = new Trick();
         $trick3->setName('Switch Backside Rodeo 720');
         $trick3->setDescription('Description de la figure Backside');
         $trick3->setMainPicture('trick9.jpg');
         $trick3->setCategory($this->getReference(CategoryFixtures::ROTATION_REFERENCE));
         $trick3->setUserCreator($this->getReference(UserFixtures::TYRIONLANNISTER_REFERENCE));
         $manager->persist($trick3);
         $this->addReference(self::TRICK3_REFERENCE, $trick3);

         $trick4 = new Trick();
         $trick4->setName('BS 540 Seatbelt');
         $trick4->setDescription('Description de la figure Seatbelt');
         $trick4->setMainPicture('trick10.jpg');
         $trick4->setCategory($this->getReference(CategoryFixtures::SLIDE_REFERENCE));
         $trick4->setUserCreator($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($trick4);
         $this->addReference(self::TRICK4_REFERENCE, $trick4);

         $trick5 = new Trick();
         $trick5->setName('Going bigger');
         $trick5->setDescription('Description de la figure Going bigger');
         $trick5->setMainPicture('trick11.jpg');
         $trick5->setCategory($this->getReference(CategoryFixtures::OLDSCHOOL_REFERENCE));
         $trick5->setUserCreator($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($trick5);
         $this->addReference(self::TRICK5_REFERENCE, $trick5);

         $trick6 = new Trick();
         $trick6->setName('Lobster Flip');
         $trick6->setDescription('Description de la figure Lobster Flip');
         $trick6->setMainPicture('trick12.jpg');
         $trick6->setCategory($this->getReference(CategoryFixtures::FLIP_REFERENCE));
         $trick6->setUserCreator($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($trick6);
         $this->addReference(self::TRICK6_REFERENCE, $trick6);

         $trick7 = new Trick();
         $trick7->setName('FS 900');
         $trick7->setDescription('Description de la figure FS 900');
         $trick7->setMainPicture('trick13.jpg');
         $trick7->setCategory($this->getReference(CategoryFixtures::SLIDE_REFERENCE));
         $trick7->setUserCreator($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($trick7);
         $this->addReference(self::TRICK7_REFERENCE, $trick7);

         $trick8 = new Trick();
         $trick8->setName('FS 360');
         $trick8->setDescription('Description de la figure FS 360');
         $trick8->setMainPicture('trick14.jpg');
         $trick8->setCategory($this->getReference(CategoryFixtures::SLIDE_REFERENCE));
         $trick8->setUserCreator($this->getReference(UserFixtures::TYRIONLANNISTER_REFERENCE));
         $manager->persist($trick8);
         $this->addReference(self::TRICK8_REFERENCE, $trick8);

         $trick9 = new Trick();
         $trick9->setName('Stalefish');
         $trick9->setDescription('Description de la figure Stalefish');
         $trick9->setMainPicture('trick15.jpg');
         $trick9->setCategory($this->getReference(CategoryFixtures::SLIDE_REFERENCE));
         $trick9->setUserCreator($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($trick9);
         $this->addReference(self::TRICK9_REFERENCE, $trick9);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
