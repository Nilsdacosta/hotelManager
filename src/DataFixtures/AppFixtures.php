<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $employe = new Employe();

        $employe->setNom('Da Costa')
            ->setPrenom('Nils')
            ->setUsername('dacostanils123')
            ->setPassword('dacostanils123')
            ->setPoste(1)
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setTelephone('0606060606');
        $manager->persist($employe);

        $manager->flush();
    }
}
