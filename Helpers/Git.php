<?php

namespace DALTCORE\ReleaseTools\Helpers;

class Git
{
    /**
     * Get current branch
     *
     * @return string
     */
    public static function branch()
    {
        return exec('git rev-parse --abbrev-ref HEAD');
    }

    /**
     * Get author info via Git
     *
     * @return string
     */
    public static function author()
    {
        return exec('git config user.name');
    }
}
