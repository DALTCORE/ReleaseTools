<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use DALTCORE\ReleaseTools\Helpers\Exceptions\GitRepoNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Init extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('init')
            // the short description shown while running "php bin/console list"
            ->setDescription('Initialize a fresh ReleaseTools instance')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command initializes a fresh ReleaseTools instance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        CLI::output($output, 'Initialize a fresh instance of ReleaseTools', CLI::INFO);
        $filesystem = new Filesystem();

        if (!$filesystem->exists('.git')) {
            throw new GitRepoNotFoundException('You are trying to initialize ReleaseTools in a Non-git environment!');
        }

        CLI::output($output, 'Check if .release-tool file exists on project level', CLI::VERB);
        if ($filesystem->exists(Constants::release_tool_file()) == false) {
            $filesystem->copy(RELEASE_TOOLS_ROOT . DIRECTORY_SEPARATOR . '.release-tool.example',
                Constants::release_tool_file());
            CLI::output($output, 'Created .release-tool file on project level', CLI::VERB, 1);
        }

        CLI::output($output, 'Check if /.release-tools directory exists on project level', CLI::VERB);
        if ($filesystem->exists(Constants::release_tool_directory()) == false) {
            $filesystem->mkdir(Constants::release_tool_directory());
            CLI::output($output, 'Created /.release-tools directory on project level', CLI::VERB, 1);
        }

        CLI::output($output, 'Check if /changelogs directory exists on project level', CLI::VERB);
        if ($filesystem->exists(Constants::changelog_dir()) == false) {
            $filesystem->mkdir(Constants::changelog_dir());
            CLI::output($output, 'Created /changelogs directory on project level', CLI::VERB, 1);
        }

        CLI::output($output, 'Check if /changelogs/unreleased directory exists on project level', CLI::VERB);
        if ($filesystem->exists(Constants::unreleased_dir()) == false) {
            $filesystem->mkdir(Constants::unreleased_dir());
            CLI::output($output, 'Created /changelogs/unreleased directory on project level', CLI::VERB, 1);
        }

        CLI::output($output, 'Check if /changelogs/released directory exists on project level', CLI::VERB);
        if ($filesystem->exists(Constants::released_dir()) == false) {
            $filesystem->mkdir(Constants::released_dir());
            CLI::output($output, 'Created /changelogs/released directory on project level', CLI::VERB, 1);
        }

        CLI::output($output, 'Check if /.release-tools/stub directory exists on project level', CLI::VERB);
        if ($filesystem->exists(Constants::project_stub_directory()) == false) {
            $filesystem->mkdir(Constants::project_stub_directory());
            CLI::output($output, 'Created /.release-tools/stub directory on project level', CLI::VERB, 1);

            CLI::output($output, 'Copying stub files from ReleaseTools to project', CLI::VERB, 1);
            $finder = new Finder();
            
            $finder->files()->in(Constants::release_tool_stub_directory());
            foreach ($finder as $file) {
                CLI::output($output, 'Copying ' . $file->getFilename(), CLI::VERB, 2);

                $filesystem->copy($file->getPathname(),
                    Constants::project_stub_directory() . DIRECTORY_SEPARATOR . $file->getFilename());
            }

        }
    }
}
