<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Status extends Command
{

    protected $readyState = true;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('status')
            // the short description shown while running "php bin/console list"
            ->setDescription('Reports the status of ReleaseTools')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Reports the status of ReleaseTools');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // $output->setVerbosity(CLI::VERB);

        CLI::output($output, 'Status of ReleaseTools', CLI::INFO);
        $filesystem = new Filesystem();
        $finder = new Finder();

        CLI::output($output, 'Check if .release-tool file exists on project level', CLI::VERB, 1);
        if ($filesystem->exists(Constants::release_tool_file())) {
            CLI::output($output, 'The .release-tool file is found', CLI::VERB, 2);
        } else {
            $this->readyState = false;
            CLI::output($output, 'The .release-tool file is not found', CLI::VERB, 2);
        }

        CLI::output($output, 'Check if /.release-tools directory exists on project level', CLI::VERB, 1);
        if ($filesystem->exists(Constants::release_tool_directory())) {
            CLI::output($output, 'The /.release-tools directory is found', CLI::VERB, 2);
        } else {
            $this->readyState = false;
            CLI::output($output, 'The /.release-tools directory is not found', CLI::VERB, 2);
        }

        CLI::output($output, 'Check if /changelogs directory exists on project level', CLI::VERB, 1);
        if ($filesystem->exists(Constants::changelog_dir())) {
            CLI::output($output, 'The /changelogs directory is found', CLI::VERB, 2);
        } else {
            $this->readyState = false;
            CLI::output($output, 'The /changelogs directory is not found', CLI::VERB, 2);
        }

        CLI::output($output, 'Check if /changelogs/unreleased directory exists on project level', CLI::VERB, 1);
        if ($filesystem->exists(Constants::unreleased_dir())) {
            CLI::output($output, 'The /changelogs/unreleased directory is found', CLI::VERB, 2);
        } else {
            $this->readyState = false;
            CLI::output($output, 'The /changelogs/unreleased directory is not found', CLI::VERB, 2);
        }

        CLI::output($output, 'Check if /changelogs/released directory exists on project level', CLI::VERB, 1);
        if ($filesystem->exists(Constants::released_dir())) {
            CLI::output($output, 'The /changelogs/released directory is found', CLI::VERB, 2);
        } else {
            $this->readyState = false;
            CLI::output($output, 'The /changelogs/released directory is not found', CLI::VERB, 2);
        }

        CLI::output($output,
            'ReleaseTools filesystem check: ' . ($this->readyState == true ? 'Valid' : 'Invalid. (run: release-tool init)'),
            CLI::INFO,
            0);

        if ($filesystem->exists(Constants::unreleased_dir())) {
            $finder->files()->in(Constants::unreleased_dir());
        } else {
            CLI::output($output, 'New changelog entries to parse: 0', CLI::INFO);
        }
    }
}
