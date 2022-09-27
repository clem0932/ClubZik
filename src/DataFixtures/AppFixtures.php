<?php

namespace App\DataFixtures;

use App\Entity\Instrument;
use App\Entity\Owner;
use App\Entity\Local;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public const NAME = 'Clubzik';


    public function load(ObjectManager $manager): void
    {
        $lieu = new Local();
        $lieu->setName("Foyer");
        $manager->persist($lieu);
        $manager->flush();

        $person = new Owner();
        $person->setNom("Cz");
        $manager->persist($person);
        $manager->flush();

        $instrument = new Instrument();
        $instrument->setInstrument("Guitare");
        $instrument->setOwner($person);
        $instrument->setlieu($lieu);
        $manager->persist($instrument);
        $manager->flush();
        $instrument = new Instrument();
        $instrument->setInstrument("Batterie");
        $instrument->setOwner($person);
        $instrument->setlieu($lieu);
        $manager->persist($instrument);
        $manager->flush();
        $instrument = new Instrument();
        $instrument->setInstrument("Piano");
        $instrument->setOwner($person);
        $instrument->setlieu($lieu);
        $manager->persist($instrument);
        $manager->flush();
        $instrument = new Instrument();
        $instrument->setInstrument("Synthe");
        $instrument->setOwner($person);
        $instrument->setlieu($lieu);
        $manager->persist($instrument);
        $manager->flush();
        

        $person = new Owner();
        $person->setNom("Clément");
        $manager->persist($person);
        $manager->flush();

        $instrument = new Instrument();
        $instrument->setInstrument("Tambourin");
        $instrument->setOwner($person);
        $instrument->setlieu($lieu);
        $manager->persist($instrument);
        $manager->flush();
        
    }
}
