<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'tce3:database-setup',
    description: "This command can be added to the composer.json file so it can run Composer's install and/or update processes.",
    hidden: false,
    aliases: ['tce3:setup-database']
)]
class DatabaseSetupCommand extends Command
{
    protected function configure(): void
    {
        $this
        ->addArgument('double_check', InputArgument::REQUIRED, 'If you want to run the script, type "Risk-Accepted". To intentionally bypass, type "bypass". (case sensitive)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $doubleCheck = $input->getArgument('double_check');

        if ($doubleCheck === 'Risk-Accepted') {

            if ($this->dumpExistingDatabaseToFile($io) != 0) {
                $io->error('Failure.');
                return Command::FAILURE;
            }

            if ($this->createDatabase($io) != 0) {
                $io->error('Failure.');
                return Command::FAILURE;
            }

            if ($this->migrateDatabase($io) != 0) {
                $io->error('Failure.');
                return Command::FAILURE;
            }

            $io->success('Success!');
            return Command::SUCCESS;
        
        } else if ($doubleCheck === 'bypass') {
            $io->success('Successfully bypassed!');
            return Command::SUCCESS;

        } else {
            $io->note('By not adding the "Risk-Accepted" argument, you declined to run the script.');
            $io->warning('Declined.');
            $io->newLine();
            return Command::INVALID;
        }

    }

    function dumpExistingDatabaseToFile(SymfonyStyle $io) {
        if (!file_exists('data/sql/backups')) {
            mkdir('data/sql/backups', 0777, true);
        };

        $scriptOutput = null;
        $returnValue = null;

        $io->section('Dumping the existing database to a sql file');
        $io->text('Executing the PHP script:');
        $io->text('mysqldump --no-tablespaces -u user -ppword schema > filename.sql');
        $io->newLine();

        $ts = date("Ymdhis");
        exec("mysqldump --no-tablespaces -u {$_ENV['DB_USERNAME']} -p{$_ENV['DB_PASSWORD']} {$_ENV['DB_SCHEMA']} > data/sql/backups/{$_ENV['DB_SCHEMA']}_{$ts}.sql", $scriptOutput, $returnValue);

        $io->text('The PHP script returned:');
        $io->text($scriptOutput);

        return $returnValue;
    }

    function createDatabase(SymfonyStyle $io) {
        $scriptOutput = null;
        $returnValue = null;

        $io->section('Creating the database');
        $io->text('Executing the PHP script:');
        $io->text('php bin/console doctrine:database:create --env='.$_ENV["APP_ENV"].' --if-not-exists');
        $io->newLine();

        exec('php bin/console doctrine:database:create --env='.$_ENV["APP_ENV"].' --if-not-exists', $scriptOutput, $returnValue);

        $io->text('The PHP script returned:');
        $io->text($scriptOutput);
        $io->newLine();

        return $returnValue;
    }

    function migrateDatabase(SymfonyStyle $io) {
        $scriptOutput = null;
        $returnValue = null;

        $io->section('Migrating the database');
        $io->text('Executing the PHP script:');
        $io->text('php bin/console doctrine:migrations:migrate -n --env='.$_ENV["APP_ENV"]);
        $io->newLine();

        exec('php bin/console doctrine:migrations:migrate -n --env='.$_ENV["APP_ENV"], $scriptOutput, $returnValue);

        $io->text('The PHP script returned:');
        $io->text($scriptOutput);
        $io->newLine();

        return $returnValue;
    }
}
