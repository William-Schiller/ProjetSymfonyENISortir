<?php

namespace App\DataFixtures;

use App\Entity\Adress;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Status;
use App\Entity\Trip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $sql = file_get_contents(__DIR__
            . '/adress.sql'
            . '/campus.sql'
            . '/city.sql'
            . '/inscription.sql'
            . '/participant.sql'
            . '/status.sql'
            . '/trip.sql');

        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute();

        $stmt->closeCursor();

        $faker = \Faker\Factory::create("fr_FR");

        $participant = new Participant();
        $participant->setMail('tagada@tsouintsouin.com');
        $participant->setPassword($this->encoder->encodePassword($participant, 'tagada'));
        $participant->setRoles(['ROLE_USER']);
        $manager->persist($participant);

        $manager->flush();

        $participant = new Participant();
        $participant->setMail('admin@tsouintsouin.com');
        $participant->setPassword($this->encoder->encodePassword($participant, 'admin'));
        $participant->setRoles(['ROLE_ADMIN']);
        $manager->persist($participant);

        $manager->flush();

        $allParticipants = $manager->getRepository(Participant::class)->findAll();

        for ($i = 0; $i < 100; $i++) {
            $status = new Status();

            $status->setName($faker->firstName);

            $manager->persist($status);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $campus = new Campus();

            $campus->setName($faker->randomLetter);

            $manager->persist($campus);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $city = new City();

            $city->setName($faker->firstName);
            $city->setPostalCode($faker->postcode);

            $manager->persist($city);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $inscription = new Inscription();

            $inscription->setParticipant($faker->randomElement($allParticipants));
            $inscription->setTrip($faker->text);

            $manager->persist($inscription);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $adress = new Adress();

            $adress->setName($faker->firstName);
            $adress->setStreet($faker->streetAddress);
            $adress->setLatitude($faker->latitude);
            $adress->setLongitude($faker->longitude);
            $adress->setCity($faker->city);

            $manager->persist($adress);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $participant = new Participant();

            $participant->setPseudo($faker->firstName);
            $participant->setRoles($faker->participant);
            $participant->setPassword($faker->password);
            $participant->setLastname($faker->lastName);
            $participant->setName($faker->firstName);
            $participant->setPhoneNumber($faker->phoneNumber);
            $participant->setMail($faker->email);
            $participant->setActive($faker->boolean);
            $participant->setCampus($faker->text);
            $participant->setAdmin($faker->u);

            $manager->persist($participant);
        }
        $manager->flush();


        for ($i = 0; $i < 100; $i++) {
            $trip = new Trip();

            $trip->setName($faker->firstName);
            $trip->setDateStart($faker->dateTimeBetween('-6 months', 'now'));
            $trip->setDuration($faker->randomDigit);
            $trip->setDateLimitInscription($faker->dateTimeBetween('now', '+ 6 months'));
            $trip->setNbMaxRegistration($faker->randomDigit);
            $trip->setInformationTrip($faker->realText(100));
            $trip->setCampus($faker->text);
            $trip->setAdress($faker->address);
            $trip->setPromoter($faker->firstName);

            $manager->persist($trip);
        }
        $manager->flush();

    }

}


