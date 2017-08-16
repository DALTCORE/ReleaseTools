<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\ArgvHandler;
use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Gitlab;
use Symfony\Component\Filesystem\Filesystem;

class prepare
{
    use ArgvHandler {
        ArgvHandler::build as protected builder;
    }

    use ConfigReader;

    protected $arguments;

    /**
     * GenerateChangelog constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        /**
         * Build expecting parameters
         */
        $this->arguments = self::builder([
            'version'
        ]);

        /**
         * Check if in the project repo a prepare.stub exists
         */
        $fs = new Filesystem();
        if ($fs->exists(PROJECT_ROOT . DIRECTORY_SEPARATOR . '.release-tools' . DIRECTORY_SEPARATOR . 'stubs'
            . DIRECTORY_SEPARATOR . 'prepare.stub')
        ) {
            $stub = file_get_contents(PROJECT_ROOT . DIRECTORY_SEPARATOR . '.release-tools' . DIRECTORY_SEPARATOR . 'stubs'
                . DIRECTORY_SEPARATOR . 'prepare.stub');
        } else {
            $stub = file_get_contents(RELEASE_TOOLS_ROOT . 'stubs' . DIRECTORY_SEPARATOR . 'prepare.stub');
        }


        $stub = str_replace(
            [':repo', ':version'],
            [self::get('repo'), $this->arguments->version],
            $stub);

        Gitlab::prepareReleaseIssue('Release version ' . $this->arguments->version, $stub);

    }
}
