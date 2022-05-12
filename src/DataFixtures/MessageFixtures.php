<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
         $message1 = new Message();
         $message1->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message1->setCreatedAt(new \DateTimeImmutable());
         $message1->setTrick($this->getReference(TrickFixtures::TRICK4_REFERENCE));
         $message1->setUserAuthor($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($message1);

         $message2 = new Message();
         $message2->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message2->setCreatedAt(new \DateTimeImmutable());
         $message2->setTrick($this->getReference(TrickFixtures::TRICK6_REFERENCE));
         $message2->setUserAuthor($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($message2);

         $message3 = new Message();
         $message3->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message3->setCreatedAt(new \DateTimeImmutable());
         $message3->setTrick($this->getReference(TrickFixtures::TRICK1_REFERENCE));
         $message3->setUserAuthor($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($message3);

         $message4 = new Message();
         $message4->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message4->setCreatedAt(new \DateTimeImmutable());
         $message4->setTrick($this->getReference(TrickFixtures::TRICK2_REFERENCE));
         $message4->setUserAuthor($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($message4);

         $message5 = new Message();
         $message5->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message5->setCreatedAt(new \DateTimeImmutable());
         $message5->setTrick($this->getReference(TrickFixtures::TRICK1_REFERENCE));
         $message5->setUserAuthor($this->getReference(UserFixtures::TYRIONLANNISTER_REFERENCE));
         $manager->persist($message5);

         $message6 = new Message();
         $message6->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message6->setCreatedAt(new \DateTimeImmutable());
         $message6->setTrick($this->getReference(TrickFixtures::TRICK4_REFERENCE));
         $message6->setUserAuthor($this->getReference(UserFixtures::TYRIONLANNISTER_REFERENCE));
         $manager->persist($message6);

         $message7 = new Message();
         $message7->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message7->setCreatedAt(new \DateTimeImmutable());
         $message7->setTrick($this->getReference(TrickFixtures::TRICK2_REFERENCE));
         $message7->setUserAuthor($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($message7);

         $message8 = new Message();
         $message8->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message8->setCreatedAt(new \DateTimeImmutable());
         $message8->setTrick($this->getReference(TrickFixtures::TRICK6_REFERENCE));
         $message8->setUserAuthor($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($message8);

         $message9 = new Message();
         $message9->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message9->setCreatedAt(new \DateTimeImmutable());
         $message9->setTrick($this->getReference(TrickFixtures::TRICK7_REFERENCE));
         $message9->setUserAuthor($this->getReference(UserFixtures::ARYASTARK_REFERENCE));
         $manager->persist($message9);

         $message10 = new Message();
         $message10->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget nunc vitae nunc tincidunt euismod. Nulla facilisi. Nulla facilisi.');
         $message10->setCreatedAt(new \DateTimeImmutable());
         $message10->setTrick($this->getReference(TrickFixtures::TRICK8_REFERENCE));
         $message10->setUserAuthor($this->getReference(UserFixtures::JONSNOW_REFERENCE));
         $manager->persist($message10);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TrickFixtures::class,
            ];
    }
}
