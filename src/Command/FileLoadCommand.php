<?php

namespace App\Command;

use App\Entity\Evaluation;
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
    name: 'files-load',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:files-load','tce3:load-files']
)]
class FileLoadCommand extends Command
{
    private array $loadableFileTypes = ['Syllabus','Document','LabSyllabus','LabDocument','AttachedFile'];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('targetFileType', InputArgument::REQUIRED, 'Indicate the entity type')
            ->addArgument('fileToProcess', InputArgument::REQUIRED, 'Indicate the file to process')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $targetFileType = $input->getArgument('targetFileType');
        $fileToProcess = $input->getArgument('fileToProcess');

        if ($targetFileType === 'Syllabus') {
            $io->title("Loading syllabi");
            return $this->loadSyllabusFiles($io, $targetFileType, $fileToProcess);
        }

        if ($targetFileType === 'Document') {
            $io->title("Loading course documents");
            return $this->loadDocumentFiles($io, $targetFileType, $fileToProcess);
        }

        if ($targetFileType === 'LabSyllabus') {
            $io->title("Loading lab syllabi");
            return $this->loadLabSyllabusFiles($io, $targetFileType, $fileToProcess);
        }

        if ($targetFileType === 'LabDocument') {
            $io->title("Loading lab documents");
            return $this->loadLabDocumentFiles($io, $targetFileType, $fileToProcess);
        }

        if ($targetFileType === 'AttachedFile') {
            $io->title("Loading attached files");
            return $this->loadAttachedFiles($io, $targetFileType, $fileToProcess);
        }

        $io->warning('Invalid.');
        return Command::INVALID;
    }

    protected function runChecks(SymfonyStyle $io, string $targetFileType, string $fileToProcess, int $exactNumOfCols, int $minNumOfRows): int {
        $io->section("Running checks");

        if ($this->checkTargetFileTypeIsLoadable($targetFileType)) {
            $io->success('Target file type is loadable.');
        } else {
            $io->warning('Cannot verify that target file type is loadable.');
            return Command::FAILURE;
        }

        if ($this->checkFileToProcessExists($targetFileType, $fileToProcess)) {
            $io->success('File to process exists.');
        } else {
            $io->warning('Cannot verify that file to process exists.');
            return Command::FAILURE;
        }

        if ($this->checkFileToProcessColumnLength($targetFileType, $fileToProcess, $exactNumOfCols)) {
            $io->success('File to process has valid column length.');
        } else {
            $io->warning('Cannot verify that file to process has valid column length.');
            return Command::FAILURE;
        }

        if ($this->checkFileToProcessRowLength($io, $targetFileType, $fileToProcess, $minNumOfRows)) {
            $io->success('File to process has valid row length.');
        } else {
            $io->warning('Cannot verify that file to process has valid row length.');
            return Command::FAILURE;
        }

        $io->newLine();
        return Command::SUCCESS;
    }

    protected function checkTargetFileTYpeIsLoadable(string $targetFileType): bool {
        return in_array($targetFileType, $this->loadableFileTypes);
    }

    protected function checkFileToProcessExists(string $targetFileType, string $fileToProcess): bool {
        return file_exists("data/files-prep/csv/{$targetFileType}/{$fileToProcess}");
    }

    protected function checkFileToProcessColumnLength(string $targetFileType, string $fileToProcess, int $exactNumOfCols): bool {
        if (($handle = fopen("data/files-prep/csv/{$targetFileType}/{$fileToProcess}", "r")) !== FALSE) {
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

    protected function checkFileToProcessRowLength(SymfonyStyle $io, string $targetFileType, string $fileToProcess, int $minNumOfRows): bool {
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/{$targetFileType}/{$fileToProcess}", "r")) !== FALSE) {
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

    protected function getExpectedNumberOfRows(string $targetFileType, string $fileToProcess): int {
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/{$targetFileType}/{$fileToProcess}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
            }
            fclose($handle);
        }
        --$row;
        return $row;
    }

    /*
     * Syllabus
     */
    protected function loadSyllabusFiles(SymfonyStyle $io, string $targetFileType, string $fileToProcess) {
        if ($this->runChecks($io, $targetFileType, $fileToProcess, 2, 2) === 0) {
            $this->parseSyllabusFileInfoAndLoadFiles($io, $fileToProcess);
        } else {
            $io->warning('Syllabus files from '.$fileToProcess.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Syllabus files from '.$fileToProcess.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseSyllabusFileInfoAndLoadFiles(SymfonyStyle $io, string $fileToProcess) {
        $io->section("Parsing csv file and moving syllabi into place");
        $denominator = $this->getExpectedNumberOfRows('Syllabus', $fileToProcess);
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/Syllabus/{$fileToProcess}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->moveSyllabusFiles($io, $data, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function moveSyllabusFiles(SymfonyStyle $io, array $row, string $total, string $current) {
        if ((int)trim($row[0]) > 362867) {
            $eval = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['d7Nid'=>$row[0]]);
            if ($eval) {

                // Parse file name
                $parts = explode("files/syllabi/", trim($row[1]));

                // Create eval dir if not exists
                /*
                if (!file_exists("data/files-prep/in-order/files/{$eval->getID()}")) {
                    mkdir("data/files-prep/in-order/files/{$eval->getID()}", 0777, true);
                }
                */
                if (!file_exists("files/{$eval->getID()}")) {
                    mkdir("files/{$eval->getID()}", 0777, true);
                }

                // Create syllabus dir inside eval dir if not exists
                /*
                if (!file_exists("data/files-prep/in-order/files/{$eval->getID()}/course_syllabus")) {
                    mkdir("data/files-prep/in-order/files/{$eval->getID()}/course_syllabus", 0777, true);
                }
                */
                if (!file_exists("files/{$eval->getID()}/course_syllabus")) {
                    mkdir("files/{$eval->getID()}/course_syllabus", 0777, true);
                }

                // Copy from out-of-order to in-order
                if (file_exists("data/files-prep/out-of-order/syllabi/{$parts[1]}")) {
                    $source = "data/files-prep/out-of-order/syllabi/{$parts[1]}";
                    // $target = "data/files-prep/in-order/files/{$eval->getID()}/course_syllabus/{$parts[1]}";
                    $target = "files/{$eval->getID()}/course_syllabus/{$parts[1]}";
                    copy($source, $target);
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at source.'
                    ));
                    return;
                }

                // Verify the move
                // if (file_exists("data/files-prep/in-order/files/{$eval->getID()}/course_syllabus/{$parts[1]}")) {
                if (file_exists("data/files-prep/in-order/files/{$eval->getID()}/course_syllabus/{$parts[1]}")) {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        $parts[1]
                    ));
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at target.'
                    ));
                }

            } else {
                $io->text(
                    sprintf("%04d/%04d\t%8s\t%64s", 
                    $current, 
                    $total, 
                    ' ',
                    "Not loaded. No evaluation."
                ));
            }
        }
    }
    // end of Syllabus methods

    /*
     * Document
     */
    protected function loadDocumentFiles(SymfonyStyle $io, string $targetFileType, string $fileToProcess) {
        if ($this->runChecks($io, $targetFileType, $fileToProcess, 2, 2) === 0) {
            $this->parseDocumentFileInfoAndLoadFiles($io, $fileToProcess);
        } else {
            $io->warning('Document files from '.$fileToProcess.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Document files from '.$fileToProcess.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseDocumentFileInfoAndLoadFiles(SymfonyStyle $io, string $fileToProcess) {
        $io->section("Parsing csv file and moving course documents into place");
        $denominator = $this->getExpectedNumberOfRows('Document', $fileToProcess);
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/Document/{$fileToProcess}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->moveDocumentFiles($io, $data, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function moveDocumentFiles(SymfonyStyle $io, array $row, string $total, string $current) {
        if ((int)trim($row[0]) > 362867) {
            $eval = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['d7Nid'=>$row[0]]);
            if ($eval) {

                // Parse file name
                $parts = explode("files/documents/", trim($row[1]));

                // Create eval dir if not exists
                if (!file_exists("files/{$eval->getID()}")) {
                    mkdir("files/{$eval->getID()}", 0777, true);
                }

                // Create course_document dir inside eval dir if not exists
                if (!file_exists("files/{$eval->getID()}/course_document")) {
                    mkdir("files/{$eval->getID()}/course_document", 0777, true);
                }

                // Copy from out-of-order to in-order
                if (file_exists("data/files-prep/out-of-order/documents/{$parts[1]}")) {
                    $source = "data/files-prep/out-of-order/documents/{$parts[1]}";
                    $target = "files/{$eval->getID()}/course_document/{$parts[1]}";
                    copy($source, $target);
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at source.'
                    ));
                    return;
                }

                // Verify the move
                if (file_exists("files/{$eval->getID()}/course_document/{$parts[1]}")) {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        $parts[1]
                    ));
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at target.'
                    ));
                }

            } else {
                $io->text(
                    sprintf("%04d/%04d\t%8s\t%64s", 
                    $current, 
                    $total, 
                    ' ',
                    "Not loaded. No evaluation."
                ));
            }
        }
    }
    // end of Document methods

    /*
     * Lab Syllabus
     */
    protected function loadLabSyllabusFiles(SymfonyStyle $io, string $targetFileType, string $fileToProcess) {
        if ($this->runChecks($io, $targetFileType, $fileToProcess, 2, 2) === 0) {
            $this->parseLabSyllabusFileInfoAndLoadFiles($io, $fileToProcess);
        } else {
            $io->warning('Lab syllabus files from '.$fileToProcess.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Lab syllabus files from '.$fileToProcess.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseLabSyllabusFileInfoAndLoadFiles(SymfonyStyle $io, string $fileToProcess) {
        $io->section("Parsing csv file and moving lab syllabi into place");
        $denominator = $this->getExpectedNumberOfRows('LabSyllabus', $fileToProcess);
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/LabSyllabus/{$fileToProcess}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->moveLabSyllabusFiles($io, $data, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function moveLabSyllabusFiles(SymfonyStyle $io, array $row, string $total, string $current) {
        if ((int)trim($row[0]) > 362867) {
            $eval = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['d7Nid'=>$row[0]]);
            if ($eval) {

                // Parse file name
                $parts = explode("files/lab_syllabi/", trim($row[1]));

                // Create eval dir if not exists
                if (!file_exists("files/{$eval->getID()}")) {
                    mkdir("files/{$eval->getID()}", 0777, true);
                }

                // Create lab syllabus dir inside eval dir if not exists
                if (!file_exists("files/{$eval->getID()}/lab_syllabus")) {
                    mkdir("files/{$eval->getID()}/lab_syllabus", 0777, true);
                }

                // Copy from out-of-order to in-order
                if (file_exists("data/files-prep/out-of-order/lab_syllabi/{$parts[1]}")) {
                    $source = "data/files-prep/out-of-order/lab_syllabi/{$parts[1]}";
                    $target = "files/{$eval->getID()}/lab_syllabus/{$parts[1]}";
                    copy($source, $target);
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at source.'
                    ));
                    return;
                }

                // Verify the move
                if (file_exists("files/{$eval->getID()}/lab_syllabus/{$parts[1]}")) {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        $parts[1]
                    ));
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at target.'
                    ));
                }

            } else {
                $io->text(
                    sprintf("%04d/%04d\t%8s\t%64s", 
                    $current, 
                    $total, 
                    ' ',
                    "Not loaded. No evaluation."
                ));
            }
        }
    }
    // end of Lab Syllabus methods

    /*
     * Lab Document
     */
    protected function loadLabDocumentFiles(SymfonyStyle $io, string $targetFileType, string $fileToProcess) {
        if ($this->runChecks($io, $targetFileType, $fileToProcess, 2, 2) === 0) {
            $this->parseLabDocumentFileInfoAndLoadFiles($io, $fileToProcess);
        } else {
            $io->warning('Lab document files from '.$fileToProcess.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Lab document files from '.$fileToProcess.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseLabDocumentFileInfoAndLoadFiles(SymfonyStyle $io, string $fileToProcess) {
        $io->section("Parsing csv file and moving lab documents into place");
        $denominator = $this->getExpectedNumberOfRows('LabDocument', $fileToProcess);
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/LabDocument/{$fileToProcess}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->moveLabDocumentFiles($io, $data, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function moveLabDocumentFiles(SymfonyStyle $io, array $row, string $total, string $current) {
        if ((int)trim($row[0]) > 362867) {
            $eval = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['d7Nid'=>$row[0]]);
            if ($eval) {

                // Parse file name
                $parts = explode("files/lab_documents/", trim($row[1]));

                // Create eval dir if not exists
                if (!file_exists("files/{$eval->getID()}")) {
                    mkdir("files/{$eval->getID()}", 0777, true);
                }

                // Create lab_document dir inside eval dir if not exists
                if (!file_exists("files/{$eval->getID()}/lab_document")) {
                    mkdir("files/{$eval->getID()}/lab_document", 0777, true);
                }

                // Copy from out-of-order to in-order
                if (file_exists("data/files-prep/out-of-order/lab_documents/{$parts[1]}")) {
                    $source = "data/files-prep/out-of-order/lab_documents/{$parts[1]}";
                    $target = "files/{$eval->getID()}/lab_document/{$parts[1]}";
                    copy($source, $target);
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at source.'
                    ));
                    return;
                }

                // Verify the move
                if (file_exists("files/{$eval->getID()}/lab_document/{$parts[1]}")) {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        $parts[1]
                    ));
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at target.'
                    ));
                }

            } else {
                $io->text(
                    sprintf("%04d/%04d\t%8s\t%64s", 
                    $current, 
                    $total, 
                    ' ',
                    "Not loaded. No evaluation."
                ));
            }
        }
    }
    // end of Lab Document methods

    /*
     * Attached File
     */
    protected function loadAttachedFiles(SymfonyStyle $io, string $targetFileType, string $fileToProcess) {
        if ($this->runChecks($io, $targetFileType, $fileToProcess, 2, 2) === 0) {
            $this->parseAttachedFileInfoAndLoadFiles($io, $fileToProcess);
        } else {
            $io->warning('Attached files from '.$fileToProcess.' have NOT been loaded.');
            return Command::FAILURE;
        }
        $io->success('Attached files from '.$fileToProcess.' have been loaded.');
        return Command::SUCCESS;
    }

    protected function parseAttachedFileInfoAndLoadFiles(SymfonyStyle $io, string $fileToProcess) {
        $io->section("Parsing csv file and moving attached files into place");
        $denominator = $this->getExpectedNumberOfRows('AttachedFile', $fileToProcess);
        $row = 0;
        if (($handle = fopen("data/files-prep/csv/AttachedFile/{$fileToProcess}", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 0) {
                    $this->moveAttachedFileFiles($io, $data, (string)$denominator, (string)$row);
                }
                $row++;
            }
            fclose($handle);
        }
        $io->newLine();
    }

    protected function moveAttachedFileFiles(SymfonyStyle $io, array $row, string $total, string $current) {
        if ((int)trim($row[0]) > 362867) {
            $eval = $this->entityManager->getRepository(Evaluation::class)->findOneBy(['d7Nid'=>$row[0]]);
            if ($eval) {

                // Parse file name
                $parts = explode("files/supplemental/", trim($row[1]));

                // Create eval dir if not exists
                if (!file_exists("files/{$eval->getID()}")) {
                    mkdir("files/{$eval->getID()}", 0777, true);
                }

                // Create attachments dir inside eval dir if not exists
                if (!file_exists("files/{$eval->getID()}/attachments")) {
                    mkdir("files/{$eval->getID()}/attachments", 0777, true);
                }

                // Copy from out-of-order to in-order
                if (file_exists("data/files-prep/out-of-order/supplemental/{$parts[1]}")) {
                    $source = "data/files-prep/out-of-order/supplemental/{$parts[1]}";
                    $target = "files/{$eval->getID()}/attachments/{$parts[1]}";
                    copy($source, $target);
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at source.'
                    ));
                    return;
                }

                // Verify the move
                if (file_exists("files/{$eval->getID()}/attachments/{$parts[1]}")) {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        $parts[1]
                    ));
                } else {
                    $io->text(
                        sprintf("%04d/%04d\t%8s\t%64s", 
                        $current, 
                        $total, 
                        $eval->getID(), 
                        'Not loaded. Problem at target.'
                    ));
                }

            } else {
                $io->text(
                    sprintf("%04d/%04d\t%8s\t%64s", 
                    $current, 
                    $total, 
                    ' ',
                    "Not loaded. No evaluation."
                ));
            }
        }
    }
    // end of Attached File methods
}
