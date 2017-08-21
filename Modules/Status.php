<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
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
        global $dispatcher;
        $event = new GenericEvent(
            $this,
            compact('input', 'output')
        );
        $dispatcher->dispatch('preflightchecks.begin', $event);

        $filesystem = new Filesystem();
        $finder = new Finder();

        CLI::output($output,
            'ReleaseTools filesystem check: ' . ($this->readyState == true ? '<fg=green>Valid</>' : '<error>Invalid</error>. (run: release-tool init)'),
            CLI::INFO,
            0);

        if ($filesystem->exists(Constants::unreleased_dir())) {
            $finder->files()->in(Constants::unreleased_dir());
        } else {
            CLI::output($output, 'New changelog entries to parse: 0', CLI::INFO);
        }
    }
}
