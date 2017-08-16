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
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'changelogs/unreleased';
    }

    /**
     * Directory path with released changelogs
     *
     * @return string
     */
    public static function released_dir()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'changelogs/released';
    }

    /**
     * Changelog file path
     *
     * @return string
     */
    public static function changelog_file()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'CHANGELOG.md';
    }

    /**
     * Release tools file path
     *
     * @return string
     */
    public static function release_tool_file()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . '.release-tool';
    }

    /**
     * Release tools directory path
     *
     * @return string
     */
    public static function release_tool_directory()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . '.release-tools';
    }

    /**
     * Projects stub directory
     *
     * @return string
     */
    public static function project_stub_directory()
    {
        return Constants::release_tool_directory() . DIRECTORY_SEPARATOR . 'stubs';
    }

    /**
     * RT stub directory
     *
     * @return string
     */
    public static function release_tool_stub_directory()
    {
        return RELEASE_TOOLS_ROOT . DIRECTORY_SEPARATOR . 'stubs';
    }
}
