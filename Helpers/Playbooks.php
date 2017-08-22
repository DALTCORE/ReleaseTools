<?php

namespace DALTCORE\ReleaseTools\Helpers;

use DALTCORE\ReleaseTools\Helpers\Exceptions\PlaybookMissingException;
use Symfony\Component\Filesystem\Filesystem;

class Playbooks
{
    /**
     * Search and give back Playbook contents
     *
     * @param $file
     *
     * @return string
     * @throws PlaybookMissingException
     */
    public static function find($file)
    {
        $fs = new Filesystem();

        if ($fs->exists(Constants::project_playbook_directory() . DIRECTORY_SEPARATOR . $file . '.rtp')) {
            return file_get_contents(Constants::project_playbook_directory() . DIRECTORY_SEPARATOR . $file . '.rtp');
        } elseif ($fs->exists(Constants::release_tool_playbook_directory() . DIRECTORY_SEPARATOR . $file . '.rtp')) {
            return file_get_contents(Constants::release_tool_playbook_directory() . DIRECTORY_SEPARATOR . $file . '.rtp');
        }

        throw new PlaybookMissingException('Missing playbook ' . $file . '.rtp');
    }

}
