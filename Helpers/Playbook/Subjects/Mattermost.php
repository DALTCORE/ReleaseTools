<?php

namespace DALTCORE\ReleaseTools\Helpers\Playbook\Subjects;

use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Playbook\CommandParameters;
use DALTCORE\ReleaseTools\Helpers\Playbook\PlaybookParameters;
use GuzzleHttp\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThibaudDauce\Mattermost\Message;

class Mattermost
{
    /**
     * Notify to Mattermost
     *
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\PlaybookParameters $options
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\CommandParameters  $parameters
     * @param \Symfony\Component\Console\Output\OutputInterface          $output
     * @param \Symfony\Component\Console\Input\InputInterface            $input
     */
    public function notify(
        PlaybookParameters $options, CommandParameters $parameters, OutputInterface $output, InputInterface $input
    ) {
        $mattermost = new \ThibaudDauce\Mattermost\Mattermost(new Client);

        dd($options->message);

        $message = (new Message)
            ->text($options->message)
            ->channel($options->channel);

        $mattermost->send($message, ConfigReader::configGet('mattermost_webhook'));
    }
}
