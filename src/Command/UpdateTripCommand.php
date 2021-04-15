<?php

namespace App\Command;

use App\Entity\Status;
use App\Entity\Trip;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateTripCommand extends Command
{
    protected static $defaultName = 'app:updateTrip';
    protected static $defaultDescription = 'update status of trips';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }


    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $tripRepository = $this->entityManager->getRepository(Trip::class);
        $trips = $tripRepository->findAll();

        $statusRepository = $this->entityManager->getRepository(Status::class);

        $statusActive = $statusRepository->findOneBy(['name'=>'Active']);
        $statusClosure = $statusRepository->findOneBy(['name'=>'Closure']);
        $statusInProgress = $statusRepository->findOneBy(['name'=>'In Progress']);
        $statusPast = $statusRepository->findOneBy(['name'=>'Past']);
        $statusDesactivate = $statusRepository->findOneBy(['name'=>'Desactivate']);

        //now +1 month
//        $dateNowMoreOneMonth = new \DateTime('now');
//        $dateNowMoreOneMonthTimeStamp = $dateNowMoreOneMonth->getTimestamp() + (60*60*24*31);
//        $dateNowMoreOneMonth->setTimestamp($dateNowMoreOneMonthTimeStamp);
        $dateNowMoreOneMonth = new DateTime('now');
        $dateNowMoreOneMonth->modify('+1 month');

        foreach($trips as $trip){
            //Desactivate General ---> NE FONCtIONNE PAS
            if($trip->getDateStart() > $dateNowMoreOneMonth){
                $trip->setStatus($statusDesactivate);
                $this->entityManager->persist($trip);
                $io->success("Trip : [" . $trip->getName() . "] is desactivate too old");
            }

            //status => Create
            if($trip->getStatus()->getName() == 'Create'){
                if($trip->getDateStart() < new \DateTime('now')){
                    $trip->setStatus($statusDesactivate);
                    $this->entityManager->persist($trip);
                    $io->success("Trip : [" . $trip->getName() . "] is desactivate");
                }
            }

            //status => Activate
            if($trip->getStatus()->getName() == 'Active'){
                if($trip->getDateLimitInscription() < new \DateTime('now')){
                    $trip->setStatus($statusClosure);
                    $this->entityManager->persist($trip);
                    $io->success("Trip : [" . $trip->getName() . "] is closure");
                }
            }

            //status => Closure
            if($trip->getStatus()->getName() == 'Closure'){
                if($trip->getDateStart() < new \DateTime('now')){
                    $trip->setStatus($statusInProgress);
                    $this->entityManager->persist($trip);
                    $io->success("Trip : [" . $trip->getName() . "] is in progress");
                }
            }

            //status => In Progress
            if($trip->getStatus()->getName() == 'In Progress'){
                $dateStart = $trip->getDateStart();
                $timeStampTripFinish = $dateStart->getTimestamp();
                $timeStampTripFinish += $trip->getDuration();
                $dateTripFinish = new \DateTime();
                $dateTripFinish->setTimestamp($timeStampTripFinish);
                if($dateTripFinish < new \DateTime('now')){
                    $trip->setStatus($statusPast);
                    $this->entityManager->persist($trip);
                    $io->success("Trip : [" . $trip->getName() . "] is Past");
                }
            }


//            $trip->setStatus($statusActive);
//            $this->entityManager->persist($trip);
        }

        $this->entityManager->flush();

        $io->writeln("WAOW LA COMMANDE DE BATARD !!!");

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
