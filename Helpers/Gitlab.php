<?php

namespace DALTCORE\ReleaseTools\Helpers;

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
        $client = \Gitlab\Client::create(ConfigReader::get('api_url'))
            ->authenticate(ConfigReader::get('api_key'), \Gitlab\Client::AUTH_URL_TOKEN);

        return $client->issues()->create(ConfigReader::get('repo'), [
            'project_id'  => ConfigReader::get('repo'),
            'title'       => $title,
            'description' => $description,
        ]);
    }
}
