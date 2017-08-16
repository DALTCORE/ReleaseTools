<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\ArgvHandler;
use DALTCORE\ReleaseTools\Helpers\Constants;
use DALTCORE\ReleaseTools\Helpers\Git;
use Symfony\Component\Yaml\Yaml;

class changelog
{
    protected $arguments;

    use ArgvHandler {
        ArgvHandler::build as protected builder;
    }

    /**
     * Create a changelog entry
     */
    public function __construct()
    {
        /**
         * Build expecting parameters
         */
        $this->arguments = self::builder([
            'title',
            'merge_request',
            'author',
        ]);

        /**
         * Build YAML file
         */
        $yaml = Yaml::dump([
            'title'         => $this->arguments->title,
            'author'        => ($this->arguments->author == null ? Git::author() : $this->arguments->author),
            'merge_request' => $this->arguments->merge_request
        ]);

        $file = Constants::unreleased_dir() . DIRECTORY_SEPARATOR . Git::branch() . '.yaml';

        if (file_exists($file)) {
            echo('Already a release file made for this branch');
            exit(1);
        } else {
            if (!file_exists(getcwd() . DIRECTORY_SEPARATOR . 'changelogs')) {
                mkdir(getcwd() . DIRECTORY_SEPARATOR . 'changelogs');
            }

            if (!file_exists(Constants::unreleased_dir())) {
                mkdir(Constants::unreleased_dir());
            }

            if (!file_exists(Constants::released_dir())) {
                mkdir(Constants::released_dir());
            }
        }

        file_put_contents($file, "---\n" . $yaml);
    }
}
