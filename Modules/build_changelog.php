<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\ArgvHandler;
use DALTCORE\ReleaseTools\Helpers\Constants;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class build_changelog
{
    use ArgvHandler {
        ArgvHandler::build as protected builder;
    }

    protected $mergeRequests = [];
    protected $prepender = '';
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
            'version'
        ]);


        $finder = new Finder();

        if (!file_exists(getcwd() . DIRECTORY_SEPARATOR . 'changelogs')) {
            mkdir(getcwd() . DIRECTORY_SEPARATOR . 'changelogs');
        }

        if (!file_exists(Constants::unreleased_dir())) {
            mkdir(Constants::unreleased_dir());
        }

        if (!file_exists(Constants::released_dir())) {
            mkdir(Constants::released_dir());
        }

        $finder->files()->in(Constants::unreleased_dir());

        /**
         * Foreach trough every merge request changelog file
         */
        foreach ($finder as $file) {
            $value = Yaml::parse(file_get_contents($file->getRealPath()));

            if (!isset($value['merge_request'])) {
                throw new \Exception('Changelog: ' . $value['title'] . ' does not have a Merge Request ID');
            }

            if (!isset($this->mergeRequests[$value['merge_request']])) {
                $this->mergeRequests[$value['merge_request']] = $value;
            } else {
                continue;
            }
        }

        /*
         * If there are no merge requests availble; just go do a poopy and let it go
         */
        if (count($this->mergeRequests) == 0) {
            echo('No merge requests for this changelog');
            exit(0);
        }

        /**
         * Build the large file string that needs to be prepended to the changelog file
         */
        $this->prepender = "## " . $this->arguments->version . " (" . date('Y-m-d') . ")  \n";
        foreach ($this->mergeRequests as $id => $value) {
            $this->prepender .= "- " . $value['title'] . " !" . $value['merge_request'] . " (" . $value['author'] . ") \n";
        }

        $changelog = Constants::changelog_file();
        if (!file_exists($changelog)) {
            touch($changelog);
        }

        /**
         * Merge changelog data
         */
        $data = file_get_contents($changelog);
        $data = $this->prepender . "\n\n" . $data;
        if (file_put_contents($changelog, $data) !== false) {
            echo "Changelog is generated!";
        }

        /**
         * Move old changelog files
         */
        foreach ($finder as $file) {
            rename($file, Constants::released_dir() . DIRECTORY_SEPARATOR . $file->getBasename());
        }

    }
}
