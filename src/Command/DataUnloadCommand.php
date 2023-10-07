<?php

namespace App\Command;

use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Evaluation;
use App\Entity\Institution;
use App\Entity\Trail;
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
    private $unloadableEntities = ['User','Course','Institution','Department','Evaluation','Trail'];

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

        if ($targetEntity === 'Course') {
            $io->title("Unloading courses");
            return $this->unloadCourses($io, $targetEntity, $fileToUnload);
        }

        if ($targetEntity === 'Institution') {
            $io->title("Unloading institutions");
            return $this->unloadInstitutions($io, $targetEntity, $fileToUnload);
        }

        if ($targetEntity === 'Department') {
            $io->title("Unloading departments");
            return $this->unloadDepartments($io, $targetEntity, $fileToUnload);
        }

        if ($targetEntity === 'Evaluation') {
            $io->title("Unloading evaluations");
            return $this->unloadEvaluations($io, $targetEntity, $fileToUnload);
        }

        if ($targetEntity === 'Trail') {
            $io->title("Unloading trails");
            return $this->unloadTrails($io, $targetEntity, $fileToUnload);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
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
        if ($targetEntity === 'Course') {
            if ($this->entityManager->getRepository(Course::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        if ($targetEntity === 'Institution') {
            if ($this->entityManager->getRepository(Institution::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        if ($targetEntity === 'Department') {
            if ($this->entityManager->getRepository(Department::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        if ($targetEntity === 'Evaluation') {
            if ($this->entityManager->getRepository(Evaluation::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        if ($targetEntity === 'Trail') {
            if ($this->entityManager->getRepository(Trail::class)->findBy(['loadedFrom'=>$fileToUnload], null, 1)) {
                return true;
            }
        }
        return false;
    }

    /*
     * User
     */
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

    /*
     * Course
     */
    protected function unloadCourses(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 2, 2) === 0) {
            $this->deleteCoursesFromCourseTable($io, $fileToUnload);
        } else {
            $io->warning('Courses from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Courses from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteCoursesFromCourseTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting courses from database");
        $courses = $this->entityManager->getRepository(Course::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($courses);
        $i = 1;
        foreach ($courses as $course) {
            $io->text(
                sprintf("%04d/%04d\t%5s\t%16s\t%16s", 
                $i, 
                $total, 
                $course->getId(), 
                $course->getSubjectCode(), 
                $course->getCourseNumber()
            ));

            $this->entityManager->remove($course);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }

    /*
     * Institution
     */
    protected function unloadInstitutions(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 8, 2) === 0) {
            $this->deleteInstitutionsFromInstitutionTable($io, $fileToUnload);
        } else {
            $io->warning('Institutions from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Institutions from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteInstitutionsFromInstitutionTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting institutions from database");
        $institutions = $this->entityManager->getRepository(Institution::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($institutions);
        $i = 1;
        foreach ($institutions as $institution) {
            $io->text(
                sprintf("%04d/%04d\t%5s\t%9s\t%6s\t%64s", 
                $i, 
                $total, 
                $institution->getId(), 
                $institution->getDapipID(), 
                $institution->getState(), 
                $institution->getName()
            ));

            $this->entityManager->remove($institution);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }

    /*
     * Department
     */
    protected function unloadDepartments(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 2, 2) === 0) {
            $this->deleteDepartmentsFromDepartmentTable($io, $fileToUnload);
        } else {
            $io->warning('Departments from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Departments from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteDepartmentsFromDepartmentTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting departments from database");
        $departments = $this->entityManager->getRepository(Department::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($departments);
        $i = 1;
        foreach ($departments as $department) {
            $io->text(
                sprintf("%04d/%04d\t%5s\t%64s", 
                $i, 
                $total, 
                $department->getId(), 
                $department->getName()
            ));

            $this->entityManager->remove($department);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }

    /*
     * Evaluation
     */
    protected function unloadEvaluations(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 2, 2) === 0) {
            $this->deleteEvaluationsFromEvaluationTable($io, $fileToUnload);
        } else {
            $io->warning('Evaluations from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Evaluations from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteEvaluationsFromEvaluationTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting evaluations from database");
        $evaluations = $this->entityManager->getRepository(Evaluation::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($evaluations);
        $i = 1;
        foreach ($evaluations as $evaluation) {
            $io->text(
                sprintf("%04d/%04d\t%5s\t%8s\t%8s\t%16s\t%8s\t%8s\t%16s\t%12s", 
                $i, 
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

            $this->entityManager->remove($evaluation);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }

    /*
     * Trail
     */
    protected function unloadTrails(SymfonyStyle $io, string $targetEntity, string $fileToUnload): int {
        if ($this->runChecks($io, $targetEntity, $fileToUnload, 2, 2) === 0) {
            $this->deleteTrailsFromTrailTable($io, $fileToUnload);
        } else {
            $io->warning('Trails from '.$fileToUnload.' have NOT been unloaded.');
            return Command::FAILURE;
        }
        $io->success('Trails from '.$fileToUnload.' have been unloaded.');
        return Command::SUCCESS;
    }

    protected function deleteTrailsFromTrailTable(SymfonyStyle $io, string $fileToUnload) {
        $io->section("Deleting trails from database");
        $trails = $this->entityManager->getRepository(Trail::class)->findBy(['loadedFrom'=>$fileToUnload]);
        $total = count($trails);
        $i = 1;
        foreach ($trails as $trail) {
            $io->text(
                sprintf("%04d/%04d\t%8s\t%8s", 
                $i, 
                $total, 
                $trail->getD7Nid(), 
                $trail->getEvaluation()->getD7Nid()
            ));

            $this->entityManager->remove($trail);
            $this->entityManager->flush();

            $i++;
        }
        $io->newLine();
    }
}
