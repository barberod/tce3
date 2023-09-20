<?php

namespace App\Command;

use App\Entity\Course;
use App\Entity\Institution;
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
    private $loadableEntities = ['Course','User','Institution'];

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

        if ($targetEntity === 'Institution') {
            $io->title("Loading institutions");
            return $this->loadInstitutions($io, $targetEntity, $fileToLoad);
        }

        if ($targetEntity === 'Course') {
            $io->title("Loading courses");
            return $this->loadCourses($io, $targetEntity, $fileToLoad);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
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

    /*
     * User
     */

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

    /*
     * Institution
     */

     protected function loadInstitutions(SymfonyStyle $io, string $targetEntity, string $fileToLoad) {
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 8, 2) === 0) {
            $this->parseInstitutionFileAndLoadInstitutions($io, $fileToLoad);
        } else {
            $io->warning('Institutions from '.$fileToLoad.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Institution from '.$fileToLoad.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseInstitutionFileAndLoadInstitutions(SymfonyStyle $io, string $fileToLoad) {
        $io->section("Parsing csv file and inserting institutions into database");
        $denominator = $this->getExpectedNumberOfNewRecords('Institution', $fileToLoad);
        $row = 0;
        if (($handle = fopen("data/csv/uploads/Institution/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->persistInstitutionToInstitutionTable($io, $data, $fileToLoad, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function persistInstitutionToInstitutionTable(SymfonyStyle $io, array $row, string $fileToLoad, string $total, string $current) {
        $institution = new Institution();

        $institution->setDapipID($row[0]);
        $institution->setUsgID('0');
        $institution->setName($row[6]);
        $institution->setState($row[5]);
        $institution->setAddress($row[2]);
        if ($row[4] == 'show') {
            $institution->setStatus(1);
        } else {
            $institution->setStatus(0);
        }
        $institution->setD7Nid($row[7]);

        $this->entityManager->persist($institution);
        $this->entityManager->flush();

        $io->text(
            sprintf("%04d/%04d\t%5s\t%9s\t%6s\t%64s", 
            $current, 
            $total, 
            $institution->getId(), 
            $institution->getDapipID(), 
            $institution->getState(), 
            $institution->getName()
        ));
    }

    /*
     * Course
     */

     protected function loadCourses(SymfonyStyle $io, string $targetEntity, string $fileToLoad) {
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 2, 2) === 0) {
            $this->parseCourseFileAndLoadCourses($io, $fileToLoad);
        } else {
            $io->warning('Courses from '.$fileToLoad.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Course from '.$fileToLoad.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseCourseFileAndLoadCourses(SymfonyStyle $io, string $fileToLoad) {
        $io->section("Parsing csv file and inserting Courses into database");
        $denominator = $this->getExpectedNumberOfNewRecords('Course', $fileToLoad);
        $row = 0;
        if (($handle = fopen("data/csv/uploads/Course/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->persistCourseToCourseTable($io, $data, $fileToLoad, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function persistCourseToCourseTable(SymfonyStyle $io, array $row, string $fileToLoad, string $total, string $current) {
        $parts = preg_split('/\s+/', $row[1]);
        $course = new Course();

        $course->setSlug($row[1]);
        $course->setSubjectCode($parts[0]);
        $course->setCourseNumber($parts[1]);
        $course->setStatus(1);
        $course->setD7Nid($row[0]);

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        $io->text(
            sprintf("%04d/%04d\t%5s\t%16s\t%16s", 
            $current, 
            $total, 
            $course->getId(), 
            $course->getSubjectCode(), 
            $course->getCourseNumber()
        ));
    }
}
