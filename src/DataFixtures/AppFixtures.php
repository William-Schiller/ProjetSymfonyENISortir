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


class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create("fr_FR");

        //STATUS
        $status = new Status();
        $status->setName('Active');
        $manager->persist($status);

        $manager->flush();

        $status = new Status();
        $status->setName('Create');
        $manager->persist($status);

        $manager->flush();

        $status = new Status();
        $status->setName('Closure');
        $manager->persist($status);

        $manager->flush();

        $status = new Status();
        $status->setName('Desactivate');
        $manager->persist($status);

        $manager->flush();

        $status = new Status();
        $status->setName('Past');
        $manager->persist($status);

        $manager->flush();

        $status = new Status();
        $status->setName('In progress');
        $manager->persist($status);

        $manager->flush();

        $allStatus = $manager->getRepository(Status::class)->findAll();

        //CAMPUS
        $campus = new Campus();
        $campus->setName('Nantes');
        $manager->persist($campus);

        $manager->flush();

        $campus = new Campus();
        $campus->setName('Rennes');
        $manager->persist($campus);

        $manager->flush();

        $campus = new Campus();
        $campus->setName('Angers');
        $manager->persist($campus);

        $manager->flush();

        $allCampus = $manager->getRepository(Campus::class)->findAll();

        //CITY
        $city = new City();
        $city->setName('MarioTown');
        $city->setPostalCode(35768);
        $manager->persist($city);

        $manager->flush();

        $city = new City();
        $city->setName('CityLol');
        $city->setPostalCode(39000);
        $manager->persist($city);

        $manager->flush();

        $city = new City();
        $city->setName('Londres');
        $city->setPostalCode(31879);
        $manager->persist($city);

        $manager->flush();

        $allCity = $manager->getRepository(City::class)->findAll();

        //ADDRESS
        $address = new Adress();
        $address->setName('Address 1');
        $address->setCity($faker->randomElement($allCity));
        $address->setLongitude('48.862725');
        $address->setLatitude('2.287592');
        $address->setStreet('1 rue des Etoiles');
        $manager->persist($address);

        $manager->flush();

        $address = new Adress();
        $address->setName('Address 2');
        $address->setCity($faker->randomElement($allCity));
        $address->setLongitude('52.51897652049578');
        $address->setLatitude('-0.7645472822265464');
        $address->setStreet('2 allées des embrumes');
        $manager->persist($address);

        $manager->flush();

        $address = new Adress();
        $address->setName('Address 3');
        $address->setCity($faker->randomElement($allCity));
        $address->setLongitude('6.723662309812199');
        $address->setLatitude('-72.83485978222654');
        $address->setStreet('3 impasse de la Rigolade');
        $manager->persist($address);

        $manager->flush();

        $allAddress = $manager->getRepository(Adress::class)->findAll();



        //PARTICIPANTS
        $participant = new Participant();
        $participant->setMail('tagada@tsouintsouin.com');
        $participant->setPassword($this->encoder->encodePassword($participant, 'tagada'));
        $participant->setRoles(['ROLE_USER']);
        $participant->setPseudo('gaga');
        $participant->setPhoneNumber('0843819234');
        $participant->setAdmin(false);
        $participant->setLastname('Momo');
        $participant->setActive(false);
        $participant->setCampus($faker->randomElement($allCampus));
        $participant->setName('hehehe');
        $manager->persist($participant);

        $manager->flush();

        $participant = new Participant();
        $participant->setMail('admin@tsouintsouin.com');
        $participant->setPassword($this->encoder->encodePassword($participant, 'admin'));
        $participant->setRoles(['ROLE_ADMIN']);
        $participant->setPseudo('Pouet');
        $participant->setPhoneNumber('0762626262');
        $participant->setAdmin(true);
        $participant->setLastname('Pouet');
        $participant->setActive(true);
        $participant->setCampus($faker->randomElement($allCampus));
        $participant->setName('Tsouin');
        $manager->persist($participant);

        $manager->flush();

        $allParticipants = $manager->getRepository(Participant::class)->findAll();

        //TRIP
        $trip = new Trip();
        $trip->setAdress($faker->randomElement($allAddress));
        $trip->setPromoter($faker->randomElement($allParticipants));
        $trip->setName('Danse');
        $trip->setCampus($faker->randomElement($allCampus));
        $trip->setStatus($faker->randomElement($allStatus));
        $trip->setDateLimitInscription($faker->dateTimeBetween('-32 months', 'now'));
        $trip->setDateStart($faker->dateTimeBetween('now', '+ 6 months'));
        $trip->setDuration($faker->randomDigit);
        $trip->setInformationTrip('Aller se promener dans les bois ...C\'est wiwi qui a eu l\'idée');
        $trip->setNbMaxRegistration($faker->randomDigit);
        $manager->persist($trip);

        $manager->flush();

        $trip = new Trip();
        $trip->setAdress($faker->randomElement($allAddress));
        $trip->setPromoter($faker->randomElement($allParticipants));
        $trip->setName('Chante');
        $trip->setCampus($faker->randomElement($allCampus));
        $trip->setStatus($faker->randomElement($allStatus));
        $trip->setDateLimitInscription($faker->dateTimeBetween('-6 months', 'now'));
        $trip->setDateStart($faker->dateTimeBetween('now', '+ 24 months'));
        $trip->setDuration($faker->randomDigit);
        $trip->setInformationTrip('Voyage dans la creuse parce qu\'a défaut de voyager ailleurs c\'est sympa la Creuse');
        $trip->setNbMaxRegistration($faker->randomDigit);
        $manager->persist($trip);

        $manager->flush();

        $trip = new Trip();
        $trip->setAdress($faker->randomElement($allAddress));
        $trip->setPromoter($faker->randomElement($allParticipants));
        $trip->setName('Ris');
        $trip->setCampus($faker->randomElement($allCampus));
        $trip->setStatus($faker->randomElement($allStatus));
        $trip->setDateLimitInscription($faker->dateTimeBetween('-12 months', 'now'));
        $trip->setDateStart($faker->dateTimeBetween('now', '+ 56 months'));
        $trip->setDuration($faker->randomDigit);
        $trip->setInformationTrip('Sortir sans masque. Ca serait vraiment bien quand même');
        $trip->setNbMaxRegistration($faker->randomDigit);
        $manager->persist($trip);

        $manager->flush();

        $allTrip = $manager->getRepository(Trip::class)->findAll();


        for ($i = 0; $i < 100; $i++) {
            $city = new City();

            $city->setName($faker->city);
            $city->setPostalCode($faker->numberBetween(10000, 99999));

            $manager->persist($city);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $address = new Adress();

            $address->setName($faker->firstName);
            $address->setStreet($faker->streetAddress);
            $address->setLatitude($faker->latitude);
            $address->setLongitude($faker->longitude);
            $address->setCity($faker->randomElement($allCity));

            $manager->persist($address);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $participant = new Participant();

            $participant->setPseudo($faker->unique()->firstName);
            $participant->setRoles((array)'ROLE_USER');
            $participant->setPassword($faker->password);
            $participant->setLastname($faker->lastName);
            $participant->setName($faker->firstName);
            $participant->setPhoneNumber($faker->numberBetween(1000000000, 9999999999));
            $participant->setMail($faker->email);
            $participant->setActive($faker->boolean);
            $participant->setCampus($faker->randomElement($allCampus));
            $participant->setAdmin(false);

            $manager->persist($participant);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $trip = new Trip();

            $trip->setName($faker->word);
            $trip->setDateStart($faker->dateTimeBetween('-6 months', 'now'));
            $trip->setDuration($faker->randomDigit);
            $trip->setDateLimitInscription($faker->dateTimeBetween('now', '+ 6 months'));
            $trip->setNbMaxRegistration($faker->randomDigit);
            $trip->setInformationTrip($faker->realText(100));
            $trip->setCampus($faker->randomElement($allCampus));
            $trip->setAdress($faker->randomElement($allAddress));
            $trip->setPromoter($faker->randomElement($allParticipants));
            $trip->setStatus($faker->randomElement($allStatus));

            $manager->persist($trip);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $inscription = new Inscription();

            $inscription->setParticipant($faker->randomElement($allParticipants));
            $inscription->setTrip($faker->randomElement($allTrip));

            $manager->persist($inscription);
        }
        $manager->flush();








    }

}


