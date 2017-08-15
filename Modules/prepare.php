<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\ArgvHandler;
use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Gitlab;

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

        $stub = file_get_contents(APP_ROOT . 'stubs' . DIRECTORY_SEPARATOR . 'prepare.stub');

        $stub = str_replace(
            [':repo', ':version'],
            [self::get('repo'), $this->arguments->version],
            $stub);

        Gitlab::prepareReleaseIssue('Release version ' . $this->arguments->version, $stub);

    }
}
