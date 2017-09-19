<?php

namespace DALTCORE\ReleaseTools\Helpers\Playbook\Subjects;

use DALTCORE\ReleaseTools\Helpers\Playbook\CommandParameters;
use DALTCORE\ReleaseTools\Helpers\Playbook\PlaybookParameters;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Gitlab
{
    /**
     * Make a merge request to GitLab
     *
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\PlaybookParameters $options
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\CommandParameters  $parameters
     * @param \Symfony\Component\Console\Output\OutputInterface          $output
     * @param \Symfony\Component\Console\Input\InputInterface            $input
     */
    public function merge_request(
        PlaybookParameters $options, CommandParameters $parameters, OutputInterface $output, InputInterface $input
    ) {

        $options->validate([
            // key      => required|regex
            'branches'  => '/^(?<from_branch>.*) > (?<to_branch>.*)/',
            'milestone' => false,
        ]);

        $output->writeln('Creating a merge request on ' . $parameters->repo);
        $output->writeln('From branch ' . $options->branches->from_branch . ' to ' .
            $options->branches->to_branch);

        if (\DALTCORE\ReleaseTools\Helpers\Gitlab::createBranch(
            $options->branches->to_branch,
            $options->branches->from_branch)
        ) {
            \DALTCORE\ReleaseTools\Helpers\Gitlab::prepareReleaseMergeRequest(
                'Merge release ' . $parameters->version,
                $options->branches->from_branch,
                $options->branches->to_branch
            );
        }
    }

    /**
     * Make a new branch give on the data
     *
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\PlaybookParameters $options
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\CommandParameters  $parameters
     * @param \Symfony\Component\Console\Output\OutputInterface          $output
     * @param \Symfony\Component\Console\Input\InputInterface            $input
     */
    public function make_branch(
        PlaybookParameters $options, CommandParameters $parameters, OutputInterface $output, InputInterface $input
    ) {
        \DALTCORE\ReleaseTools\Helpers\Gitlab::createBranch($options->to, $options->from);
    }

    /**
     * Make a new tag give on the data
     *
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\PlaybookParameters $options
     * @param \DALTCORE\ReleaseTools\Helpers\Playbook\CommandParameters  $parameters
     * @param \Symfony\Component\Console\Output\OutputInterface          $output
     * @param \Symfony\Component\Console\Input\InputInterface            $input
     */
    public function tag(
        PlaybookParameters $options, CommandParameters $parameters, OutputInterface $output, InputInterface $input
    ) {
        $description = "Release of version " . $parameters->version;
        $rev = $options->from;
        $version = $parameters->version;
        \DALTCORE\ReleaseTools\Helpers\Gitlab::createTag($description, $rev, $version);
    }
}
