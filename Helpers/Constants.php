<?php

namespace DALTCORE\ReleaseTools\Helpers;

class Constants
{
    /**
     * Directory path with unreleased changelogs
     *
     * @return string
     */
    public static function unreleased_dir()
    {
        return getcwd() . DIRECTORY_SEPARATOR . 'changelogs/unreleased';
    }

    /**
     * Directory path with released changelogs
     *
     * @return string
     */
    public static function released_dir()
    {
        return getcwd() . DIRECTORY_SEPARATOR . 'changelogs/released';
    }

    /**
     * Changelog file path
     *
     * @return string
     */
    public static function changelog_file()
    {
        return getcwd() . DIRECTORY_SEPARATOR . 'CHANGELOG.md';
    }
}
