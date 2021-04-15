<?php

namespace App\Command;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateRolesCommand extends Command
{
    protected static $defaultName = 'app:updateRoles';
    protected static $defaultDescription = 'Add a short description for your command';

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

        $userRepository = $this->entityManager->getRepository(Participant::class);
        $users = $userRepository->findAll();

        foreach($users as $user){
            if($user->getAdmin()){
                $roles = ['ROLE_ADMIN', 'ROLE_USER'];
                $user->setRoles($roles);
            }else{
                if($user->getActive()){
                    $roles = ['ROLE_USER'];
                    $user->setRoles($roles);
                } else {
                    $roles = [];
                    $user->setRoles($roles);
                }
            }
            $this->entityManager->persist($user);
            $io->success($user->getName() . ' is update');
        }

        $this->entityManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
