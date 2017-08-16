<?php

namespace DALTCORE\ReleaseTools\Helpers;

use DALTCORE\ReleaseTools\Helpers\Exceptions\StubMissingException;
use Symfony\Component\Filesystem\Filesystem;

class Stubs
{

    const PREPARE = 'prepare.stub';

    /**
     * Search and give back Stub contents
     *
     * @param $file
     * @return string
     * @throws StubMissingException
     */
    public static function find($file)
    {
        $fs = new Filesystem();
        if ($fs->exists(Constants::project_stub_directory() . DIRECTORY_SEPARATOR . $file)) {
            return file_get_contents(Constants::project_stub_directory() . DIRECTORY_SEPARATOR . $file);
        } elseif ($fs->exists(Constants::release_tool_stub_directory() . DIRECTORY_SEPARATOR . $file)) {
            return file_get_contents(Constants::release_tool_stub_directory() . DIRECTORY_SEPARATOR . $file);
        }

        throw new StubMissingException('Missing stub ' . $file);
    }

}
