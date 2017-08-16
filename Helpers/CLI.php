<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Symfony\Component\Console\Output\OutputInterface;

class CLI {

    const INFO = 32;
    const VERB = 64;

    /**
     * Write command to CLI
     *
     * @param OutputInterface $output
     * @param $message
     * @param $verb
     */
    public static function output(OutputInterface $output, $message, $verb, $level = 0) {
        if($output->getVerbosity() >= $verb) {

            if(CLI::VERB == $verb) {
                $i = 0;
                $prefix = '|';
                while ($i <= $level) {
                    $prefix .= "--";
                    $i++;
                }
                $output->writeLn($prefix . '[ReleaseTools] - ' . $message);
            } else {
                $output->writeLn('[ReleaseTools] - ' . $message);
            }

        }
    }

}