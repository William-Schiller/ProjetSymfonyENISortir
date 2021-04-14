<?php

namespace App\Command;

use App\Entity\Trip;
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

        foreach($trips as $trip){
            $io->writeln($trip->getName());
        }

        $io->writeln("WAOW LA COMMANDE DE BATARD !!!");

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
