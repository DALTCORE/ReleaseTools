<?php

namespace DALTCORE\ReleaseTools\Helpers;

class Constants
{

    /**
     * Directory path with changelogs
     *
     * @return string
     */
    public static function changelog_dir()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'changelogs';
    }

    /**
     * Directory path with unreleased changelogs
     *
     * @return string
     */
    public static function unreleased_dir()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'changelogs' . DIRECTORY_SEPARATOR . 'unreleased';
    }

    /**
     * Directory path with released changelogs
     *
     * @return string
     */
    public static function released_dir()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . 'changelogs' . DIRECTORY_SEPARATOR . 'released';
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
     * @return string
     */
    public static function current_changelog()
    {
        return Constants::unreleased_dir() . DIRECTORY_SEPARATOR . str_replace(['/', '\\',], '-',
                Git::branch()) . '.yaml';
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

    /**
     * @return string
     */
    public static function project_git()
    {
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . '.git';
    }

    /**
     * @return string
     */
    public static function project_git_hooks()
    {
        return Constants::project_git() . DIRECTORY_SEPARATOR . 'hooks';
    }

    /**
     * @return string
     */
    public static function project_git_pre_commit_hook_file()
    {
        return Constants::project_git_hooks() . DIRECTORY_SEPARATOR . 'pre-commit';
    }

    /**
     * @return string
     */
    public static function release_tools_git_hooks()
    {
        return RELEASE_TOOLS_ROOT . DIRECTORY_SEPARATOR . 'hooks';
    }

    /**
     * @return string
     */
    public static function release_tools_pre_commit_hook_file()
    {
        return Constants::release_tools_git_hooks() . DIRECTORY_SEPARATOR . 'pre-commit';
    }
}
