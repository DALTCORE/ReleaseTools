<?php

namespace DALTCORE\ReleaseTools\Modules\Hooks;

use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MadeChangelog extends Command
{

    use ConfigReader;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('hooks:made-changelog')
            // the short description shown while running "php bin/console list"
            ->setDescription('Hook for git pre-commit to check if changelog is made')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Hook for git pre-commit to check if ')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('cli', InputArgument::OPTIONAL, 'CLI output'),
                )));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if ($input->getArgument('cli')) {
            $output->write(Constants::current_changelog());
            exit(0);
        }

        $finder = new Finder();
        $fileSystem = new Filesystem();

        if (!$fileSystem->exists(Constants::project_git_pre_commit_hook_file())) {
            $fileSystem->touch(Constants::project_git_pre_commit_hook_file());
            $fileSystem->appendToFile(Constants::project_git_pre_commit_hook_file(), "#!/bin/bash\n\n");
        }

        $data = file_get_contents(Constants::release_tools_pre_commit_hook_file());

        $fileSystem->appendToFile(Constants::project_git_pre_commit_hook_file(), $data);

        $process = new Process('chmod +x ' . Constants::project_git_pre_commit_hook_file());
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
