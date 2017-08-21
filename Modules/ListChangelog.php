<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ListChangelog extends Command
{

    protected $mergeRequests = [];
    protected $changelogs = [];

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('list:changelog')
            // the short description shown while running "php bin/console list"
            ->setDescription('List unreleased changelogs')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('List unreleased changelogs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        global $dispatcher;
        $event = new GenericEvent(
            $this,
            compact('input', 'output')
        );
        $dispatcher->dispatch('preflightchecks.begin', $event);


        $finder = new Finder();
        $filesystem = new Filesystem();

        if (!$filesystem->exists(Constants::changelog_dir())) {
            $filesystem->mkdir(Constants::changelog_dir());
        }

        if (!$filesystem->exists(Constants::unreleased_dir())) {
            $filesystem->mkdir(Constants::unreleased_dir());
        }

        if (!$filesystem->exists(Constants::released_dir())) {
            $filesystem->mkdir(Constants::released_dir());
        }

        $finder->files()->in(Constants::unreleased_dir());

        $table = new Table($output);

        foreach ($finder as $file) {
            $value = Yaml::parse(file_get_contents($file->getRealPath()));
            $this->changelogs[] = $value;
        }

        $table->setHeaders(array('Title', 'Author', 'Merge Request', 'Type'))->setRows($this->changelogs);
        $table->render();
    }
}
