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
    name: 'data-load',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:data-load','tce3:load-data']
)]
class DataLoadCommand extends Command
{
    private $loadableEntities = ['User'];

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
            ->addArgument('fileToLoad', InputArgument::REQUIRED, 'Indicate the file to load')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $targetEntity = $input->getArgument('targetEntity');
        $fileToLoad = $input->getArgument('fileToLoad');

        if ($targetEntity === 'User') {
            $io->title("Loading users");
            return $this->loadUsers($io, $targetEntity, $fileToLoad);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
    }

    protected function loadUsers(SymfonyStyle $io, string $targetEntity, string $fileToLoad) {
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 8, 2) === 0) {
            $this->parseUserFileAndLoadUsers($io, $fileToLoad);
        } else {
            $io->warning('Users from '.$fileToLoad.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Users from '.$fileToLoad.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseUserFileAndLoadUsers(SymfonyStyle $io, string $fileToLoad) {
        $io->section("Parsing csv file and inserting users into database");
        $denominator = $this->getExpectedNumberOfNewRecords('User', $fileToLoad);
        $row = 0;
        if (($handle = fopen("data/csv/uploads/User/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->persistUserToUserTable($io, $data, $fileToLoad, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function persistUserToUserTable(SymfonyStyle $io, array $row, string $fileToLoad, string $total, string $current) {
        $user = new User();

        $user->setUsername($row[0]);
        $user->setOrgID($row[1]);
        $user->setDisplayName($row[2]);
        $user->setEmail($row[3]);
        $user->setCategory($row[4]);
        $user->setStatus((int) $row[5]);
        $user->setFrozen((int) $row[6]);
        $user->setLoadedFrom($fileToLoad);

        $rolesToLoad = [User::ROLE_USER];
        if (!empty($row[7])) {
            $roleArr = explode(',', $row[7]);
            $rolesToLoad = $this->getRolesToLoad($roleArr);
        };
        $user->setRoles($rolesToLoad);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->text(
            sprintf("%04d/%04d\t%s\t%15s\t%s\t%30s\t%s", 
            $current, 
            $total, 
            $user->getId(), 
            $user->getUsername(), 
            $user->getOrgID(), 
            $user->getDisplayName(),
            implode(',',$user->getRoles())
        ));
    }

    protected function runChecks(SymfonyStyle $io, string $targetEntity, string $fileToLoad, int $exactNumOfCols, int $minNumOfRows): int {
        $io->section("Running checks");

        if ($this->checkTargetEntityIsLoadable($targetEntity)) {
            $io->success('Target entity is loadable.');
        } else {
            $io->warning('Cannot verify that target entity is loadable.');
            return Command::FAILURE;
        }

        if ($this->checkFileToLoadExists($targetEntity, $fileToLoad)) {
            $io->success('File to load exists.');
        } else {
            $io->warning('Cannot verify that file to load exists.');
            return Command::FAILURE;
        }

        if ($this->checkFileToLoadColumnLength($targetEntity, $fileToLoad, $exactNumOfCols)) {
            $io->success('File to load has valid column length.');
        } else {
            $io->warning('Cannot verify that file to load has valid column length.');
            return Command::FAILURE;
        }

        if ($this->checkFileToLoadRowLength($io, $targetEntity, $fileToLoad, $minNumOfRows)) {
            $io->success('File to load has valid row length.');
        } else {
            $io->warning('Cannot verify that file to load has valid row length.');
            return Command::FAILURE;
        }

        $io->newLine();
        return Command::SUCCESS;
    }

    protected function checkTargetEntityIsLoadable(string $targetEntity): bool {
        return in_array($targetEntity, $this->loadableEntities);
    }

    protected function checkFileToLoadExists(string $targetEntity, string $fileToLoad): bool {
        return file_exists("data/csv/uploads/{$targetEntity}/{$fileToLoad}");
    }

    protected function checkFileToLoadColumnLength(string $targetEntity, string $fileToLoad, int $exactNumOfCols): bool {
        if (($handle = fopen("data/csv/uploads/{$targetEntity}/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) === $exactNumOfCols) {
                    fclose($handle);
                    return true;
                }
            }
            fclose($handle);
        }
        return false;
    }

    protected function checkFileToLoadRowLength(SymfonyStyle $io, string $targetEntity, string $fileToLoad, int $minNumOfRows): bool {
        $row = 0;
        if (($handle = fopen("data/csv/uploads/{$targetEntity}/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                for ($col=0; $col < count($data); $col++) {
                    // $io->text('Cell #'.$row.'-'.($col+1).': '.$data[$col]);
                }
                $row++;
            }
            // $io->text('Row length = '.$row);
            if ($row >= $minNumOfRows) {
                fclose($handle);
                return true;
            }
            fclose($handle);
        }
        return false;
    }

    protected function getExpectedNumberOfNewRecords(string $targetEntity, string $fileToLoad): int {
        $row = 0;
        if (($handle = fopen("data/csv/uploads/{$targetEntity}/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
            }
            fclose($handle);
        }
        --$row;
        return $row;
    }

    protected function getRolesToLoad(array $roleArr): array {
        $rolesToLoad = [];
        foreach ($roleArr as $role) {
            switch ($role) {
                case 'ROLE_ADMIN':
                    $rolesToLoad[] = User::ROLE_ADMIN;
                    break;
                case 'ROLE_FACULTY':
                    $rolesToLoad[] = User::ROLE_FACULTY;
                    break;
                case 'ROLE_STAFF':
                    $rolesToLoad[] = User::ROLE_STAFF;
                    break;
                case 'ROLE_STUDENT':
                    $rolesToLoad[] = User::ROLE_STUDENT;
                    break;
                case 'ROLE_GSAPP':
                    $rolesToLoad[] = User::ROLE_GSAPP;
                    break;
                case 'ROLE_UGAPP':
                    $rolesToLoad[] = User::ROLE_UGAPP;
                    break;
                default:
                    $rolesToLoad[] = User::ROLE_USER;
                    break;
            }
        }
        return $rolesToLoad;
    }
}
