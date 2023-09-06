<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'tce3:example',
    description: 'An example of a command',
    hidden: false,
    aliases: ['tce3:run-example','tce3:show-example']
)]
class ExampleCommand extends Command
{
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This is an example of a command.')

            // configure an argument
            ->addArgument('first_name', InputArgument::REQUIRED, 'Provide your first name')
            ->addArgument('double_check', InputArgument::REQUIRED, 'If you want to run the script, type "YES" (case sensitive)')
            ->addArgument('progress_bar_size', InputArgument::OPTIONAL, 'Indicate the size of the progress bar to demo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstName = $input->getArgument('first_name');
        $doubleCheck = $input->getArgument('double_check');
        $progressBarSize = $input->getArgument('progress_bar_size');
        
        $io = new SymfonyStyle($input, $output);

        $io->title('Example Command');

        $io->section('Handling Input');
        $io->note(sprintf('Hello, %s.', $firstName));
        if ($doubleCheck === 'YES') {
            $io->text('Because you typed "YES" the script will run.');
        } else {
            $io->text('The script will not run because you did not type "YES" (case sensitive).');
        }
        if ($progressBarSize) {
            $io->text('You will see a progress bar of size '.$progressBarSize);
        } else {
            $io->text('You will see no progress bar.');
        }

        $io->section('Demonstrating the progress bar');
        if ($progressBarSize) {
            if (!is_numeric($progressBarSize)) {
                throw new \RuntimeException('The size of the progress bar must be a valid number.');
            }

            $this->demoProgressBar($io, (int)$progressBarSize);
            $io->note('You saw a progress bar of size '.$progressBarSize);
        } else {
            $io->warning('Declined.');
            $io->note('You opted not to see a progress bar.');
        }

        $io->section('Optional demonstrations');
        $this->demoListing($io);
        $this->demoTable($io);
        $this->demoHorizontalTable($io);
        $this->demoDefinitionList($io);

        $io->section('Running a PHP script');
        if ($doubleCheck === 'YES') {
            $scriptOutput = null;
            $returnValue = null;
            exec('php bin/console about -n', $scriptOutput, $returnValue);

            if ($returnValue === 0) {
                $io->success('Success!');
                $io->text($scriptOutput);
                return Command::SUCCESS;
            } else {
                $io->error('Failure.');
                $io->text($scriptOutput);
                return Command::FAILURE;
            }
        } else {
            $io->warning('Declined.');
            $io->text('You opted not to run the script.');
            $io->newLine();
            return Command::INVALID;
        }
    }

    function demoListing(SymfonyStyle $io) {
        $userListing = $io->confirm('Do you want to see a demo of a listing?', false);
        if ($userListing) {
            $io->section('Demonstrating a listing');
            $io->listing([
                'Element #1 Lorem ipsum dolor sit amet',
                'Element #2 Lorem ipsum dolor sit amet',
                'Element #3 Lorem ipsum dolor sit amet',
            ]);
        }
    }

    function demoTable(SymfonyStyle $io) {
        $userTable = $io->confirm('Do you want to see a demo of a table?', false);
        if ($userTable) {
            $io->section('Demonstrating a table');
            $io->table(
                ['Header 1', 'Header 2'],
                [
                    ['Cell 1-1', 'Cell 1-2'],
                    ['Cell 2-1', 'Cell 2-2'],
                    ['Cell 3-1', 'Cell 3-2'],
                ]
            );
        }
    }

    function demoHorizontalTable(SymfonyStyle $io) {
        $userHorizontalTable = $io->confirm('Do you want to see a demo of a horizontal table?', false);
        if ($userHorizontalTable) {
            $io->section('Demonstrating a horizontal table');
            $io->horizontalTable(
                ['Header 1', 'Header 2'],
                [
                    ['Cell 1-1', 'Cell 1-2'],
                    ['Cell 2-1', 'Cell 2-2'],
                    ['Cell 3-1', 'Cell 3-2'],
                ]
            );
        }
    }

    function demoDefinitionList(SymfonyStyle $io) {
        $userDefinitionList = $io->confirm('Do you want to see a demo of a definition list?', false);
        if ($userDefinitionList) {
            $io->section('Demonstrating a definition list');
            $io->definitionList(
                'This is a title',
                ['foo1' => 'bar1'],
                ['foo2' => 'bar2'],
                ['foo3' => 'bar3']
            );
        }
    }

    function demoProgressBar(SymfonyStyle $io, int $numberOfSteps) {
        foreach ($io->progressIterate($this->generateIterable($numberOfSteps)) as $value) {
            sleep(1);
            $io->text('Step:'.$value.'!');
            $io->newLine();
        }
    }

    function generateIterable(int $numberOfItems): array {
        $iterable = [];
        for ($i = 1; $i <= $numberOfItems; $i++) {
            $iterable[] = $i;
        }
        return $iterable;
    }
}
