<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Symfony\Component\Filesystem\Filesystem;

class Stubs
{

    /**
     * Release tool stub
     *
     * @param $file
     *
     * @return bool|string
     */
    public static function find($file)
    {
        $fs = new Filesystem();
        if ($fs->exists(Constants::project_stub_directory() . DIRECTORY_SEPARATOR . $file)) {
            return Constants::project_stub_directory() . DIRECTORY_SEPARATOR . $file;
        } elseif ($fs->exists(Constants::release_tool_stub_directory() . DIRECTORY_SEPARATOR . $file)) {
            return Constants::release_tool_stub_directory() . DIRECTORY_SEPARATOR . $file;
        }

        return false;
    }

}
