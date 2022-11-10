<?php

namespace App\DataFixtures;

use App\Entity\Instrument;
use App\Entity\Local;
use App\Entity\Owner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @return \\Generator
     */
    private static function InstrumentsDataGenerator()
    {
        yield ["Guitare", "Local Club Zik", "Club Zik"];
        yield ["Batterie", "Local Club Zik", "Club Zik"];
        yield ["Piano", "Local Club Zik", "Club Zik"];
        yield ["Synthé", "Local Club Zik", "Club Zik"];
        yield ["Tambourin", "Local Club Zik", "Clément"];
        yield ["Pied de mico", "Chez Denzel", "Club Zik"];
        yield ["Alto", "Local Club Zik", "Alix"];
    }

    /**
     * @return \\Generator
     */
    private static function OwnersGenerator()
    {
        yield ["Clément"];
        yield ["Alix"];
        yield ["Denzel"];
        yield ["Club Zik"];
    }

    /**
     * @return \\Generator
     */
    private static function LocationsGenerator()
    {
        yield ["Local Club Zik"];
        yield ["Chez Denzel"];
        yield ["Chez Alix"];
        yield ["Chez Clément"];
    }

    public function load(ObjectManager $manager)
    {
        $ownerRepo = $manager->getRepository(Owner::class);
        foreach (self::OwnersGenerator() as [$name]) {
            $owner = new Owner();
            $owner->setName($name);
            $manager->persist($owner);
        }
        $manager->flush();

        $locationRepo = $manager->getRepository(Local::class);
        foreach (self::LocationsGenerator() as [$name]) {
            $location = new Local();
            $location->setName($name);
            $manager->persist($location);
        }
        $manager->flush();

        foreach (self::InstrumentsDataGenerator() as [$name, $locationName, $ownerName]) {
            $owner = $ownerRepo->findOneBy(['name' => $ownerName]);
            $location = $locationRepo->findOneBy(['name' => $locationName]);
            $instru = new Instrument();
            $instru->setName($name);
            $instru->setOwner($owner);
            $instru->setLieu($location);
            $manager->persist($instru);
        }
        $manager->flush();
    }
}
