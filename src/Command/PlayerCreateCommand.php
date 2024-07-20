<?php

namespace App\Command;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:player:create', description: 'Create a new player')]
class PlayerCreateCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    )
    {
        parent::__construct();
    }

    #[\Override]
    protected function configure()
    {
        $this
            ->setDescription('Create one or more players')
            ->addArgument('name', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The player name')
        ;
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $names = $input->getArgument('name');

        foreach ($names as $name) {
            $player = new Player();
            $player->setName($name);
            $errors = $this->validator->validate($player);
            if (\count($errors) > 0) {
                throw new ValidationFailedException((string) $errors, $errors);
            }
            $this->entityManager->persist($player);
            $io->success(sprintf('Player %s created (id: %s)', $player->getName(), $player->getId()));
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
