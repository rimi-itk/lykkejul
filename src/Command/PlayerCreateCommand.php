<?php

namespace App\Command;

use App\Entity\Player;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PlayerCreateCommand extends Command
{
    protected static $defaultName = 'app:player:create';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create one or more players')
            ->addArgument('name', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The player name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $names = $input->getArgument('name');

        foreach ($names as $name) {
            $player = new Player();
            $player->setName($name);
            $this->entityManager->persist($player);
            $io->success(sprintf('Player %s created (id: %s)', $player->getName(), $player->getId()));
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
