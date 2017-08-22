<?php

namespace DALTCORE\ReleaseTools\Helpers;

use Symfony\Component\Console\Output\OutputInterface;

class CLI
{

    const INFO = 32;
    const VERB = 64;

    /**
     * Write command to CLI
     *
     * @param OutputInterface $output
     * @param                 $message
     * @param                 $verb
     */
    public static function output(OutputInterface $output, $message, $verb, $level = 0)
    {
        if ($output->getVerbosity() >= $verb) {

            if (CLI::VERB == $verb && $level > 0) {
                $i = 0;
                $prefix = '<info>|</info>';
                while ($i <= $level) {
                    $prefix .= "<info>--</info>";
                    $i++;
                }
                $output->writeLn($prefix . '<info>[ReleaseTools]</info> - ' . $message);
            } else {
                $output->writeLn('<info>[ReleaseTools]</info> - ' . $message);
            }

        }
    }

}
