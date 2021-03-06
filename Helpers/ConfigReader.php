<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Symfony\Component\Yaml\Yaml;

trait ConfigReader
{
    /**
     * Get a key from release-tools file
     *
     * @param null $key
     *
     * @return mixed
     */
    public static function configGet($key = null)
    {
        $value = Yaml::parse(file_get_contents(PROJECT_ROOT . DIRECTORY_SEPARATOR . '.release-tool'));

        return $value[$key];
    }
}
