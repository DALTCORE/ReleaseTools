<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\ArgvHandler;
use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Filesystem\Filesystem;

class init
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
        ]);

        $fs = new Filesystem();

        /**
         * Copy .release-tools file if not exists
         */
        if (!$fs->exists(Constants::release_tool_file())) {
            $fs->copy(RELEASE_TOOLS_ROOT . DIRECTORY_SEPARATOR . '.release-tool.example',
                Constants::release_tool_file());
        }

        /**
         * Make .release-tools and .release-tools/stub directory
         */
        if (!$fs->exists(Constants::release_tool_directory())) {
            $fs->mkdir(Constants::release_tool_directory());
            $fs->mkdir(Constants::project_stub_directory());
        }
    }
}
