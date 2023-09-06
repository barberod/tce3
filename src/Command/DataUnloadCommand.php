<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'data-unload',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:data-unload','tce3:unload-data']
)]
class DataUnloadCommand extends Command
{
    private $unloadableEntities = ['User'];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('targetEntity', InputArgument::REQUIRED, 'Indicate the entity type')
            ->addArgument('fileToUnload', InputArgument::REQUIRED, 'Indicate the file to unload')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $targetEntity = $input->getArgument('targetEntity');
        $fileToUnload = $input->getArgument('fileToUnload');

        if ($targetEntity === 'User') {
            $io->title("Unloading users");
            return $this->unloadUsers($io, $targetEntity, $fileToUnload);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
    }

    protected function unloadUsers(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 8, 2) === 0) {
            $this->deleteUsersFromUserTable($io, $fileToUnload);
        } else {
            $io->warning('Users from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Users from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteUsersFromUserTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting users from database");
        $users = $this->entityManager->getRepository(User::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($users);
        $i = 1;
        foreach ($users as $user) {
            $io->text(
                sprintf("%04d/%04d\t%s\t%15s\t%s\t%30s\t%s", 
                $i, 
                $total, 
                $user->getId(), 
                $user->getUsername(), 
                $user->getOrgID(), 
                $user->getDisplayName(),
                implode(',',$user->getRoles())
            ));

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }

    protected function runChecks(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        $io->section("Running checks");
        if ($this->checkTargetEntityIsUnloadable($targetEntity)) {
            $io->success('Target entity is unloadable.');
        } else {
            $io->warning('Cannot verify that target entity is unloadable.');
            return Command::FAILURE;
        }

        if ($this->checkFileToUnloadIsLoaded($targetEntity, $fileToUnload)) {
            $io->success('File to unload is loaded.');
        } else {
            $io->warning('Cannot verify that file to unload is loaded.');
            return Command::FAILURE;
        }
        $io->newLine();
        return Command::SUCCESS;
    }

    protected function checkTargetEntityIsUnloadable(string $targetEntity): bool {
        return in_array($targetEntity, $this->unloadableEntities);
    }

    protected function checkFileToUnloadIsLoaded(string $targetEntity, string $fileToUnload): bool {
        if ($targetEntity === 'User') {
            if ($this->entityManager->getRepository(User::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        return false;
    }
}
