<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Symfony\Component\Yaml\Yaml;

trait ArgvHandler
{
    protected static $argv = [];

    /**
     * Build argv array
     *
     * @param $args
     *
     * @return $this
     */
    public function build($args)
    {
        global $argv;

        self::$argv['application'] = $argv[0];
        self::$argv['module'] = $argv[1];

        unset($argv[0], $argv[1]);

        $argv = array_values($argv);
        foreach ($argv as $key => $argument) {
            $argument = str_replace(["'", '"'], '', $argument);

            if (isset($args[$key])) {
                self::$argv[$args[$key]] = $argument;
            } else {
                self::$argv[$key - 2] = $argument;
            }
        }

        return $this;
    }

    /**
     * Magic to get arg from class
     *
     * @param $name
     *
     * @return null
     */
    public function __get($name)
    {
        if (isset(self::$argv[$name])) {
            return self::$argv[$name];
        }

        return null;
    }

    /**
     * In case of var_dump
     *
     * @return array
     */
    function dump()
    {
        return Yaml::dump(self::$argv);
    }
}
