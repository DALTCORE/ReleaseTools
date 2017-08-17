<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use DALTCORE\ReleaseTools\Helpers\Exceptions\ChangelogExistsException;
use DALTCORE\ReleaseTools\Helpers\Git;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Changelog extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('changelog')
            // the short description shown while running "php bin/console list"
            ->setDescription('Add changelog entry')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Add a new changelog entry file')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('title', InputArgument::REQUIRED, 'Changelog entry title'),
                    new InputArgument('merge_request_id', InputArgument::REQUIRED, 'Merge request ID'),
                    new InputArgument('author', InputArgument::OPTIONAL, 'Merge request author'),
                )));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        CLI::output($output, 'Creating changelog entry', CLI::INFO);

        CLI::output($output, 'Building YAML file', CLI::VERB, 1);
        $yaml = Yaml::dump([
            'title'         => $input->getArgument('title'),
            'author'        => ($input->getArgument('author') == null ? Git::author() : $input->getArgument('author')),
            'merge_request' => $input->getArgument('merge_request_id')
        ]);

        CLI::output($output, 'Building YAML file path', CLI::VERB, 1);
        $file = Constants::current_changelog();

        CLI::output($output, 'Check if file already exists', CLI::VERB, 1);
        $fs = new Filesystem();
        if ($fs->exists($file)) {
            CLI::output($output, 'About to throw up', CLI::VERB, 1);
            throw new ChangelogExistsException('Changelog already exists!');
        }

        CLI::output($output, 'Created changelog file', CLI::VERB, 2);
        $fs->dumpFile($file, $yaml);
    }
}
