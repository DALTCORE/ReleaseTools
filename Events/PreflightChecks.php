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

        $error = false;

        // $event['output']->setVerbosity(CLI::VERB);

        if (!$filesystem->exists(Constants::project_git())) {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], '<error>Warning: .git ('.Constants::project_git().') directory does not exist in this project!</error>',
                CLI::VERB,
                0);
        }

        if ($filesystem->exists(Constants::release_tool_file())) {
            CLI::output($event['output'], 'Release tool file: <fg=yellow>'.Constants::release_tool_file().'</>: <fg=green>OK</>', CLI::VERB, 0);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'Release tool file: <fg=yellow>'.Constants::release_tool_file().'</>: <fg=red>ERROR</>', CLI::VERB, 0);
            $error = true;
        }

        if ($filesystem->exists(Constants::release_tool_directory())) {
            CLI::output($event['output'], 'Release tool dir: <fg=yellow>'.Constants::release_tool_directory().'</>: <fg=green>OK</>', CLI::VERB, 0);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'Release tool dir: <fg=yellow>'.Constants::release_tool_directory().'</>: <fg=red>ERROR</>', CLI::VERB, 0);
            $error = true;
        }

        if ($filesystem->exists(Constants::changelog_dir())) {
            CLI::output($event['output'], 'Changelog dir: <fg=yellow>'.Constants::changelog_dir().'</>: <fg=green>OK</>', CLI::VERB, 0);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'Changelog dir: <fg=yellow>'.Constants::changelog_dir().'</>: <fg=red>ERROR</>', CLI::VERB, 0);
            $error = true;
        }

        if ($filesystem->exists(Constants::unreleased_dir())) {
            CLI::output($event['output'], 'Unreleased dir: <fg=yellow>'.Constants::unreleased_dir().'</>: <fg=green>OK</>', CLI::VERB, 0);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'Unreleased dir: <fg=yellow>'.Constants::unreleased_dir().'</>: <fg=red>ERROR</>', CLI::VERB, 0);
            $error = true;
        }

        if ($filesystem->exists(Constants::released_dir())) {
            CLI::output($event['output'], 'Released dir: <fg=yellow>'.Constants::released_dir().'</>: <fg=green>OK</>', CLI::VERB, 0);
        } else {
            $event['output']->setVerbosity(CLI::VERB);
            CLI::output($event['output'], 'Released dir: <fg=yellow>'.Constants::released_dir().'</>: <fg=red>ERROR</>', CLI::VERB, 0);
            $error = true;
        }

        if($error)
        {
            CLI::output($event['output'], 'Try running: "release-tool init" from this directory', CLI::VERB, 0);
            exit(1);
        }
    }
}
