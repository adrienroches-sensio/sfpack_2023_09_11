<?php

namespace App\Omdb\Bridge\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:movies:import',
    description: 'Search and import one or more movies into the database.',
    aliases: [
        'omdb:movies:import',
        'movies:import',
    ]
)]
class MoviesImportCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('id-or-title', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'IMDB ID or title to search.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Will not import into database. Only displays what would happen.')
            ->setHelp(<<<'EOT'
                The <info>%command.name%</info> import movies data from OMDB API to database :
                
                Using only titles
                    <info>php %command.full_name% "title 1" "title 2"</info>
                
                Using only ids
                    <info>php %command.full_name% "ttid1" "ttid2"</info>
                
                Or mixing both
                    <info>php %command.full_name% "ttid1" "title 2"</info>
                EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
