<?php

namespace App\Command;

use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Evaluation;
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
    private array $loadableEntities = ['User','Course','Institution','Department','Evaluation'];

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

        if ($targetEntity === 'Department') {
            $io->title("Loading departments");
            return $this->loadDepartments($io, $targetEntity, $fileToLoad);
        }

        if ($targetEntity === 'Evaluation') {
            $io->title("Loading evaluations");
            return $this->loadEvaluations($io, $targetEntity, $fileToLoad);
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
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 9, 2) === 0) {
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
        $user->setD7Uid((int) $row[8]);

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

                case 'manager':
                    $rolesToLoad[] = User::ROLE_STAFF;
                    $rolesToLoad[] = User::ROLE_MANAGER;
                    break;
                case 'ro':
                    $rolesToLoad[] = User::ROLE_STAFF;
                    $rolesToLoad[] = User::ROLE_COORDINATOR;
                    break;
                case 'facstaff':
                    $rolesToLoad[] = User::ROLE_FACULTY;
                    $rolesToLoad[] = User::ROLE_ASSIGNEE;
                    break;
                case 'admissions':
                    $rolesToLoad[] = User::ROLE_STAFF;
                    $rolesToLoad[] = User::ROLE_OBSERVER;
                    break;
                case 'student':
                    $rolesToLoad[] = User::ROLE_STUDENT;
                    $rolesToLoad[] = User::ROLE_REQUESTER;
                    break;

                default:
                    $rolesToLoad[] = User::ROLE_USER;
                    break;
            }
        }
        return $rolesToLoad;
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

        if (!isset($parts[1])) {
            return;
        }
        if (strlen($row[1]) > 12) {
            return;
        }

        $course = new Course();
        $course->setSlug(str_replace(" ","",$row[1]));
        $course->setSubjectCode($parts[0]);
        $course->setCourseNumber($parts[1]);
        $course->setStatus(1);
        $course->setD7Nid($row[0]);
        $course->setLoadedFrom($fileToLoad);

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
        $institution->setLoadedFrom($fileToLoad);

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
     * Department
     */
    protected function loadDepartments(SymfonyStyle $io, string $targetEntity, string $fileToLoad) {
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 2, 2) === 0) {
            $this->parseDepartmentFileAndLoadDepartments($io, $fileToLoad);
        } else {
            $io->warning('Departments from '.$fileToLoad.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Department from '.$fileToLoad.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseDepartmentFileAndLoadDepartments(SymfonyStyle $io, string $fileToLoad) {
        $io->section("Parsing csv file and inserting departments into database");
        $denominator = $this->getExpectedNumberOfNewRecords('Department', $fileToLoad);
        $row = 0;
        if (($handle = fopen("data/csv/uploads/Department/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->persistDepartmentToDepartmentTable($io, $data, $fileToLoad, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function persistDepartmentToDepartmentTable(SymfonyStyle $io, array $row, string $fileToLoad, string $total, string $current) {
        $department = new Department();

        $department->setName($row[0]);
        $department->setStatus(1);
        $department->setD7Nid($row[1]);
        $department->setLoadedFrom($fileToLoad);

        $this->entityManager->persist($department);
        $this->entityManager->flush();

        $io->text(
            sprintf("%04d/%04d\t%5s\t%64s", 
            $current, 
            $total, 
            $department->getId(), 
            $department->getName()
        ));
    }

    /*
     * Evaluation
     */
    protected function loadEvaluations(SymfonyStyle $io, string $targetEntity, string $fileToLoad) {
        if ($this->runChecks($io, $targetEntity, $fileToLoad, 60, 2) === 0) {
            $this->parseEvaluationFileAndLoadEvaluations($io, $fileToLoad);
        } else {
            $io->warning('Evaluations from '.$fileToLoad.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Evaluation from '.$fileToLoad.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseEvaluationFileAndLoadEvaluations(SymfonyStyle $io, string $fileToLoad) {
        $io->section("Parsing csv file and inserting evaluations into database");
        $denominator = $this->getExpectedNumberOfNewRecords('Evaluation', $fileToLoad);
        $row = 0;
        if (($handle = fopen("data/csv/uploads/Evaluation/{$fileToLoad}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->persistEvaluationToEvaluationTable($io, $data, $fileToLoad, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function persistEvaluationToEvaluationTable(SymfonyStyle $io, array $row, string $fileToLoad, string $total, string $current) {
        $evaluation = new Evaluation();

        $evaluation->setSerialNum($row[1]);

        $requester = $this->entityManager->getRepository(User::class)->findOneBy(['d7Uid'=>$row[2]]);
        if ($requester) {
            $evaluation->setRequester($requester);
        }
        
        $evaluation->setReqAdmin($row[3]);

        // $evaluation->setInstitution($row[4]);
        $institution = $this->entityManager->getRepository(Institution::class)->findOneBy(['d7Nid'=>$row[41]]);
        if ($institution) {
            $evaluation->setInstitution($institution);
        }

        $evaluation->setInstitutionOther($row[5]);
        
        $evaluation->setInstitutionCountry($row[6]);
        $evaluation->setCourseSubjCode($row[7]);
        $evaluation->setCourseCrseNum($row[8]);
        $evaluation->setCourseTerm($row[9]);
        $evaluation->setCourseCreditHrs($row[10]);

        $evaluation->setCourseCreditBasis($row[11]);
        $evaluation->setLabSubjCode($row[12]);
        $evaluation->setLabCrseNum($row[13]);
        $evaluation->setLabTerm($row[14]);
        $evaluation->setLabCreditHrs($row[15]);

        $evaluation->setLabCreditBasis($row[16]);
        $evaluation->setPhase($row[17]);

        // $evaluation->setStatus($row[18]);
        if (trim($row[18]) == "Yes") {
            $evaluation->setStatus(1);
        } else {
            $evaluation->setStatus(0);
        }

        // $evaluation->setCreated($row[19]);
        $dt = date_create_from_format("Y-m-d H:i:s", trim($row[19]));
        if ($dt) {
            $evaluation->setCreated($dt);
        }

        // updated is set by the database

        // $evaluation->setAssignee($row[20]);
        $assignee = $this->entityManager->getRepository(User::class)->findOneBy(['d7Uid'=>$row[20]]);
        if ($assignee) {
            $evaluation->setAssignee($assignee);
        }

        $evaluation->setDraftEquiv1Course(trim($row[21]));
        $evaluation->setDraftEquiv1CreditHrs($row[22]);
        $evaluation->setDraftEquiv1Operator($row[23]);

        $evaluation->setDraftEquiv2Course(trim($row[24]));
        $evaluation->setDraftEquiv2CreditHrs($row[25]);
        $evaluation->setDraftEquiv2Operator($row[26]);

        $evaluation->setDraftEquiv3Course(trim($row[27]));
        $evaluation->setDraftEquiv3CreditHrs($row[28]);
        $evaluation->setDraftEquiv3Operator($row[29]);

        $evaluation->setDraftEquiv4Course(trim($row[30]));
        $evaluation->setDraftEquiv4CreditHours($row[31]);

        $evaluation->setDraftPolicy($row[32]);

        // $evaluation->setFinalEquiv1Course($row[33]);
        $equiv1 = $this->entityManager->getRepository(Course::class)->findOneBy(['d7Nid'=>$row[33]]);
        if ($equiv1) {
            $evaluation->setFinalEquiv1Course($equiv1);
        }

        $evaluation->setFinalEquiv1CreditHrs($row[34]);
        $evaluation->setFinalEquiv1Operator($row[35]);

        // $evaluation->setFinalEquiv2Course($row[36]);
        $equiv2 = $this->entityManager->getRepository(Course::class)->findOneBy(['d7Nid'=>$row[36]]);
        if ($equiv2) {
            $evaluation->setFinalEquiv2Course($equiv2);
        }

        $evaluation->setFinalEquiv2CreditHrs($row[37]);
        $evaluation->setFinalEquiv2Operator($row[38]);

        // $evaluation->setFinalEquiv3Course($row[39]);
        $equiv3 = $this->entityManager->getRepository(Course::class)->findOneBy(['d7Nid'=>$row[39]]);
        if ($equiv3) {
            $evaluation->setFinalEquiv3Course($equiv3);
        }

        $evaluation->setFinalEquiv3CreditHrs($row[40]);
        $evaluation->setFinalEquiv3Operator($row[41]);

        // $evaluation->setFinalEquiv4Course($row[42]);
        $equiv4 = $this->entityManager->getRepository(Course::class)->findOneBy(['d7Nid'=>$row[42]]);
        if ($equiv4) {
            $evaluation->setFinalEquiv4Course($equiv4);
        }

        $evaluation->setFinalEquiv4CreditHrs($row[43]);

        $evaluation->setFinalPolicy($row[44]);
        $evaluation->setRequesterType($row[45]);

        // $evaluation->setCourseInSis($row[46]);       superfluous
        // $evaluation->setTranscriptOnHand($row[47]);  superfluous

        // $evaluation->setHoldForRequesterAdmit($row[48]);
        if (trim($row[48]) == "Yes") {
            $evaluation->setHoldForRequesterAdmit(1);
        } else {
            $evaluation->setHoldForRequesterAdmit(0);
        }

        // $evaluation->setHoldForCourseInput($row[49]);
        if (trim($row[49]) == "Yes") {
            $evaluation->setHoldForCourseInput(1);
        } else {
            $evaluation->setHoldForCourseInput(0);
        }

        // $evaluation->setHoldForTranscript($row[50]);
        if (trim($row[50]) == "Yes") {
            $evaluation->setHoldForTranscript(1);
        } else {
            $evaluation->setHoldForTranscript(0);
        }

        // $evaluation->setTagSpotArticulated($row[51]);
        if (trim($row[51]) == "Yes") {
            $evaluation->setTagSpotArticulated(1);
        } else {
            $evaluation->setTagSpotArticulated(0);
        }

        // $evaluation->setTagR1ToStudent($row[52]);
        if (trim($row[52]) == "Yes") {
            $evaluation->setTagR1ToStudent(1);
        } else {
            $evaluation->setTagR1ToStudent(0);
        }

        // $evaluation->setTagDeptToStudent($row[53]);
        if (trim($row[53]) == "Yes") {
            $evaluation->setTagDeptToStudent(1);
        } else {
            $evaluation->setTagDeptToStudent(0);
        }

        // $evaluation->setTagDeptToR1($row[54]);
        if (trim($row[54]) == "Yes") {
            $evaluation->setTagDeptToR1(1);
        } else {
            $evaluation->setTagDeptToR1(0);
        }

        // $evaluation->setTagR2ToStudent($row[55]);
        if (trim($row[55]) == "Yes") {
            $evaluation->setTagR2ToStudent(1);
        } else {
            $evaluation->setTagR2ToStudent(0);
        }

        // $evaluation->setTagR2ToDept($row[56]);
        if (trim($row[56]) == "Yes") {
            $evaluation->setTagR2ToDept(1);
        } else {
            $evaluation->setTagR2ToDept(0);
        }

        // $evaluation->setTagReassigned($row[57]);
        if (trim($row[57]) == "Yes") {
            $evaluation->setTagReassigned(1);
        } else {
            $evaluation->setTagReassigned(0);
        }

        $evaluation->setD7Nid($row[0]);
        $evaluation->setD7Uuid($row[58]);

        $evaluation->setLoadedFrom($fileToLoad);

        // $evaluation->setUpdated($row[59]);
        $dt2 = date_create_from_format("Y-m-d H:i:s", trim($row[59]));
        if ($dt2) {
            $evaluation->setUpdated($dt2);
        }

        $this->entityManager->persist($evaluation);
        $this->entityManager->flush();

        $io->text(
            sprintf("%04d/%04d\t%5s\t%8s\t%8s\t%16s\t%8s\t%8s\t%16s\t%12s", 
            $current, 
            $total, 
            $evaluation->getId(),
            $evaluation->getSerialNum(),
            $evaluation->getD7Nid(),
            $evaluation->getRequester()->getUsername(),
            $evaluation->getCourseSubjCode(),
            $evaluation->getCourseCrseNum(),
            date_format($evaluation->getCreated(),"Y-m-d"),
            $evaluation->getPhase(),
        ));
    }
}
