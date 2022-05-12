<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
        public CONST GRAB_REFERENCE = 'category-grab';
        public CONST FLIP_REFERENCE = 'category-flip';
        public CONST ROTATION_REFERENCE = 'category-rotation';
        public CONST SLIDE_REFERENCE = 'category-slide';
        public CONST ONEFOOT_REFERENCE = 'category-onefoot';
        public CONST OLDSCHOOL_REFERENCE = 'category-oldschool';

    public function load(ObjectManager $manager): void
    {
        $grab = new Category();
        $grab->setName('Grab');
        $grab->setPicture('trick1.jpg');
        $manager->persist($grab);
        $this->addReference(self::GRAB_REFERENCE, $grab);

        $flip = new Category();
        $flip->setName('Flip');
        $flip->setPicture('trick2.jpg');
        $manager->persist($flip);
        $this->addReference(self::FLIP_REFERENCE, $flip);

        $rotation = new Category();
        $rotation->setName('Rotation');
        $rotation->setPicture('trick3.jpg');
        $manager->persist($rotation);
        $this->addReference(self::ROTATION_REFERENCE, $rotation);

        $slide = new Category();
        $slide->setName('Slide');
        $slide->setPicture('trick4.jpg');
        $manager->persist($slide);
        $this->addReference(self::SLIDE_REFERENCE, $slide);

        $oneFoot = new Category();
        $oneFoot->setName('One Foot');
        $oneFoot->setPicture('trick5.jpg');
        $manager->persist($oneFoot);
        $this->addReference(self::ONEFOOT_REFERENCE, $oneFoot);

        $oldSchool = new Category();
        $oldSchool->setName('Old School');
        $oldSchool->setPicture('trick6.jpg');
        $manager->persist($oldSchool);
        $this->addReference(self::OLDSCHOOL_REFERENCE, $oldSchool);

        $manager->flush();
    }
}
