<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Gitlab\Client;

class Gitlab
{
    use ConfigReader;

    /**
     * Post issue to gitlab issue tracker from repo
     *
     * @param $title
     * @param $description
     *
     * @return mixed
     */
    public static function prepareReleaseIssue($title, $description)
    {

        $client = new Client(ConfigReader::configGet('api_url'). '/api/v3/');
        $client->authenticate(ConfigReader::configGet('api_key'), Client::AUTH_URL_TOKEN);

        $project = new \Gitlab\Model\Project(ConfigReader::configGet('repo'), $client);

        return $project->createIssue($title, [
            'description' => $description,
        ]);
    }
}
