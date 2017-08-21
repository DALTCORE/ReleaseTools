<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class BuildChangelog extends Command
{

    protected $mergeRequests = [];
    protected $prepender = '';

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('build:changelog')
            // the short description shown while running "php bin/console list"
            ->setDescription('Prepend changelog entries to changelog')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Prepend changelog entries to changelog')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('version', InputArgument::REQUIRED, 'Version to prepend'),
                )));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        global $dispatcher;
        $event = new GenericEvent(
            $this,
            compact('input', 'output')
        );
        $dispatcher->dispatch('preflightchecks.begin', $event);

        CLI::output($output, 'Creating CHANGELOG.md', CLI::INFO);

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

        foreach ($finder as $file) {
            $value = Yaml::parse(file_get_contents($file->getRealPath()));
            if (!isset($value['merge_request'])) {
                throw new \Exception('Changelog: ' . $value['title'] . ' does not have a Merge Request ID');
            }
            $this->mergeRequests[$value['type']][] = $value;
        }

        /**
         * If there are no merge requests availble; just go do a poopy and let it go
         */
        if (count($this->mergeRequests) == 0) {
            CLI::output($output, 'No changelog entries to parse!', CLI::VERB, 1);
            exit(0);
        }

        /**
         * Build the large file string that needs to be prepended to the changelog file
         */
        $this->prepender = "## " . $input->getArgument('version') . " (" . date('Y-m-d') . ")  \n";
        foreach ($this->mergeRequests as $type => $items) {
            $this->prepender .= "**" . $type . "**  \n";

            foreach ($items as $value) {
                $this->prepender .= "- " . $value['title'] . " [!" . $value['merge_request'] . "] (" .
                    $value['author'] . ") \n";
            }
            $this->prepender .= "\n";
        }

        foreach ($this->mergeRequests as $type => $items) {
            foreach ($items as $value) {
                $this->prepender .= "[!" . $value['merge_request'] . "]: <" . ConfigReader::configGet('api_url') . "/" .
                    ConfigReader::configGet('repo') . "/merge_requests/" . $value['merge_request'] . "> \"!" .
                    $value['merge_request'] . "\"" . PHP_EOL;
            }
        }

        // (" . ConfigReader::configGet('api_url') . "/". ConfigReader::configGet('repo') . "/merge_requests/" . $value['merge_request'] . ")

        if (!$filesystem->exists(Constants::changelog_file())) {
            $filesystem->touch(Constants::changelog_file());
        }

        /**
         * Merge changelog data
         */
        $data = file_get_contents(Constants::changelog_file());
        $data = $this->prepender . "\n\n" . $data;
        if ($filesystem->dumpFile(Constants::changelog_file(), $data) !== false) {
            CLI::output($output, 'Changelog is created!', CLI::VERB, 1);
        }

        /**
         * Move old changelog files
         */
        foreach ($finder as $file) {
            $filesystem->rename($file, Constants::released_dir() . DIRECTORY_SEPARATOR . $file->getBasename());
        }
    }
}
