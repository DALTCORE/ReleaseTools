<?php

namespace DALTCORE\ReleaseTools\Events;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Filesystem;

class PreflightChecks
{
    /**
     * @param \Symfony\Component\EventDispatcher\Event           $event
     * @param                                                    $name
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    public function begin(Event $event, $name, EventDispatcher $eventDispatcher)
    {
        $filesystem = new Filesystem();

        // $event['output']->setVerbosity(CLI::VERB);

        if (!$filesystem->exists(Constants::project_git())) {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], '<error>Warning: .git directory does not exist in this project!</error>',
                CLI::VERB,
                2);
        }

        if ($filesystem->exists(Constants::release_tool_file())) {
            CLI::output($event['output'], 'The .release-tool file is found', CLI::VERB, 2);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'The .release-tool file is not found', CLI::VERB, 2);
            CLI::output($event['output'], 'Try running: "release-tool init" from this directory', CLI::VERB, 3);
            exit(1);
        }

        if ($filesystem->exists(Constants::release_tool_directory())) {
            CLI::output($event['output'], 'The /.release-tools directory is found', CLI::VERB, 2);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'The /.release-tools directory is not found', CLI::VERB, 2);
            CLI::output($event['output'], 'Try running: "release-tool init" from this directory', CLI::VERB, 3);
            exit(1);
        }

        if ($filesystem->exists(Constants::changelog_dir())) {
            CLI::output($event['output'], 'The /changelogs directory is found', CLI::VERB, 2);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'The /changelogs directory is not found', CLI::VERB, 2);
            CLI::output($event['output'], 'Try running: "release-tool init" from this directory', CLI::VERB, 3);
            exit(1);
        }

        if ($filesystem->exists(Constants::unreleased_dir())) {
            CLI::output($event['output'], 'The /changelogs/unreleased directory is found', CLI::VERB, 2);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'The /changelogs/unreleased directory is not found', CLI::VERB, 2);
            CLI::output($event['output'], 'Try running: "release-tool init" from this directory', CLI::VERB, 3);
            exit(1);
        }

        if ($filesystem->exists(Constants::released_dir())) {
            CLI::output($event['output'], 'The /changelogs/released directory is found', CLI::VERB, 2);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'The /changelogs/released directory is not found', CLI::VERB, 2);
            CLI::output($event['output'], 'Try running: "release-tool init" from this directory', CLI::VERB, 3);
            exit(1);
        }
    }
}
