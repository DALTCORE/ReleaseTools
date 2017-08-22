<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Gitlab\Api\Tags;
use Gitlab\Client;

class Gitlab
{
    use ConfigReader;

    /**
     * Post issue to GitLab
     *
     * @param $title
     * @param $description
     *
     * @return \Gitlab\Model\Issue
     */
    public static function prepareReleaseIssue($title, $description)
    {
        $client = new Client(ConfigReader::configGet('api_url') . '/api/v3/');
        $client->authenticate(ConfigReader::configGet('api_key'), Client::AUTH_URL_TOKEN);

        $project = new \Gitlab\Model\Project(ConfigReader::configGet('repo'), $client);

        return $project->createIssue($title, [
            'description' => $description,
        ]);
    }

    /**
     * Post mergerequest to Gitlab
     *
     * @param      $title
     * @param      $source
     * @param      $target
     * @param null $assignee
     * @param null $description
     *
     * @return \Gitlab\Model\MergeRequest
     */
    public static function prepareReleaseMergeRequest($title, $source, $target, $assignee = null, $description = null)
    {
        $client = new Client(ConfigReader::configGet('api_url') . '/api/v3/');
        $client->authenticate(ConfigReader::configGet('api_key'), Client::AUTH_URL_TOKEN);

        $project = new \Gitlab\Model\Project(ConfigReader::configGet('repo'), $client);

        return $project->createMergeRequest($source, $target, $title, $assignee, $description);
    }

    /**
     * Post new branch to Gitlab
     *
     * @param      $name
     * @param      $ref
     *
     * @return \Gitlab\Model\MergeRequest
     */
    public static function createBranch($name, $ref)
    {
        $client = new Client(ConfigReader::configGet('api_url') . '/api/v3/');
        $client->authenticate(ConfigReader::configGet('api_key'), Client::AUTH_URL_TOKEN);

        $project = new \Gitlab\Model\Project(ConfigReader::configGet('repo'), $client);

        return $project->createBranch($name, $ref);
    }

    /**
     * Create new tag
     *
     * @param $description
     * @param $rev
     * @param $version
     */
    public static function createTag($description, $rev, $version)
    {
        $client = new Client(ConfigReader::configGet('api_url') . '/api/v3/');
        $client->authenticate(ConfigReader::configGet('api_key'), Client::AUTH_URL_TOKEN);

        $project = new \Gitlab\Model\Project(ConfigReader::configGet('repo'), $client);

        $tag = new Tags($client);
        dd($tag->create($project->id, [
            'tag_name'    => 'v' . $version,
            'ref'         => $rev,
            'description' => $description
        ]));
    }
}
