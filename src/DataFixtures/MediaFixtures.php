<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
         $media1 = new Media();
         $media1->setTitle('media1');
         $media1->setType('Image');
         $media1->setUploadedPath('trick3.jpg');
         $media1->setTrick($this->getReference(TrickFixtures::TRICK1_REFERENCE));
         $manager->persist($media1);

         $media2 = new Media();
         $media2->setTitle('media2');
         $media2->setType('Image');
         $media2->setUploadedPath('trick4.jpg');
         $media2->setTrick($this->getReference(TrickFixtures::TRICK1_REFERENCE));
         $manager->persist($media2);

         $media3 = new Media();
         $media3->setTitle('media3');
         $media3->setType('Vidéo téléchargée');
         $media3->setUploadedPath('video1.mp4');
         $media3->setTrick($this->getReference(TrickFixtures::TRICK2_REFERENCE));
         $manager->persist($media3);

         $media4 = new Media();
         $media4->setTitle('media4');
         $media4->setType('Vidéo streamée');
         $media4->setStreamedPath('https://www.youtube.com/watch?v=t705_V-RDcQ');
         $media4->setTrick($this->getReference(TrickFixtures::TRICK3_REFERENCE));
         $manager->persist($media4);

         $media5 = new Media();
         $media5->setTitle('media5');
         $media5->setType('Image');
         $media5->setUploadedPath('trick7.jpg');
         $media5->setTrick($this->getReference(TrickFixtures::TRICK3_REFERENCE));
         $manager->persist($media5);

         $media6 = new Media();
         $media6->setTitle('media6');
         $media6->setType('Vidéo streamée');
         $media6->setStreamedPath('https://www.youtube.com/watch?v=t705_V-RDcQ');
         $media6->setTrick($this->getReference(TrickFixtures::TRICK4_REFERENCE));
         $manager->persist($media6);

        $media7 = new Media();
        $media7->setTitle('media7');
        $media7->setType('Vidéo téléchargée');
        $media7->setUploadedPath('video1.mp4');
        $media7->setTrick($this->getReference(TrickFixtures::TRICK3_REFERENCE));
        $manager->persist($media7);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
            ];
    }
}
