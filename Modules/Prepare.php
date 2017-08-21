<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\ArgvHandler;
use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Constants;
use DALTCORE\ReleaseTools\Helpers\Gitlab;
use DALTCORE\ReleaseTools\Helpers\Stubs;
use Gitlab\Exception\MissingArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Prepare extends Command
{

    use ConfigReader;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('release:prepare')

            // the short description shown while running "php bin/console list"
            ->setDescription('Prepare a release')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Prepeare a release with version and interactive messages and GitLab issues')

            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('version', InputArgument::REQUIRED, 'Version to handle in this release'),
                )))

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        global $dispatcher;
        $event = new GenericEvent(
            $this,
            compact('input', 'output')
        );
        $dispatcher->dispatch('preflightchecks.begin', $event);

        CLI::output($output, 'Setting up release v' . $input->getArgument('version') . ' for ' . self::configGet('repo'), CLI::INFO, 0);


        CLI::output($output, 'Searching for stub: ' . Stubs::PREPARE, CLI::VERB, 1);

        $stub = Stubs::find(Stubs::PREPARE);

        CLI::output($output, 'Filling stub with data' , CLI::VERB, 1);
        $stub = str_replace(
            [':repo', ':version'],
            [self::configGet('repo'), $input->getArgument('version')],
            $stub);

        CLI::output($output, 'Push stub to GitLab' , CLI::INFO, 1);
        Gitlab::prepareReleaseIssue('Release version ' . $input->getArgument('version'), $stub);
    }
}
