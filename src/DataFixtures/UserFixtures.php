<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const JONSNOW_REFERENCE = 'user-jonsnow';
    public const ARYASTARK_REFERENCE = 'user-aryastark';
    public const TYRIONLANNISTER_REFERENCE = 'user-tyrionlannister';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $jonSnow = new User();
        $jonSnow->setFirstName('Jon');
        $jonSnow->setLastName('Snow');
        $jonSnow->setUserName('JSW');
        $jonSnow->setEmail('jon-snow@gmail.com');
        $jonSnow->setPassword($this->hasher->hashPassword($jonSnow, 'winteriscoming'));
        $jonSnow->setRoles(['ROLE_ADMIN']);
        $jonSnow->setProfilePicture('profile-jon-snow.png');
        $jonSnow->setIsVerified(true);
        $manager->persist($jonSnow);
        $this->addReference(self::JONSNOW_REFERENCE, $jonSnow);

        $aryaStark = new User();
        $aryaStark->setFirstName('Arya');
        $aryaStark->setLastName('Stark');
        $aryaStark->setUserName('Aiguille');
        $aryaStark->setEmail('arya-stark@gmail.com');
        $aryaStark->setPassword($this->hasher->hashPassword($aryaStark, 'personne1234'));
        $aryaStark->setRoles(['ROLE_USER']);
        $aryaStark->setProfilePicture('profile3.jpg');
        $aryaStark->setIsVerified(true);
        $manager->persist($aryaStark);
        $this->addReference(self::ARYASTARK_REFERENCE, $aryaStark);

        $tyrionLannister = new User();
        $tyrionLannister->setFirstName('Tyrion');
        $tyrionLannister->setLastName('Lannister');
        $tyrionLannister->setUserName('Main du Roi');
        $tyrionLannister->setEmail('tyrion-lannister@gmail.com');
        $tyrionLannister->setPassword($this->hasher->hashPassword($tyrionLannister, 'mainduroi1234'));
        $tyrionLannister->setRoles(['ROLE_USER']);
        $tyrionLannister->setProfilePicture('profile-type.png');
        $tyrionLannister->setIsVerified(true);
        $manager->persist($tyrionLannister);
        $this->addReference(self::TYRIONLANNISTER_REFERENCE, $tyrionLannister);

        $manager->flush();
    }
}
