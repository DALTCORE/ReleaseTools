<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\ConfigReader;
use DALTCORE\ReleaseTools\Helpers\Gitlab;
use DALTCORE\ReleaseTools\Helpers\Stubs;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\EventDispatcher\GenericEvent;

class Prepare extends Command
{

    use ConfigReader;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('release:prepare')
            // the short description shown while running "php bin/console list"
            ->setDescription('Prepare a release')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Prepeare a release with version and interactive messages and GitLab issues')
            ->setDefinition(
                new InputDefinition(array(
                    new InputOption('dry-run', 'dr', InputOption::VALUE_OPTIONAL, 'Dry Run method', false),
                )));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        global $dispatcher;
        $event = new GenericEvent(
            $this,
            compact('input', 'output')
        );
        $dispatcher->dispatch('preflightchecks.begin', $event);

        $helper = $this->getHelper('question');
        $question = new Question('Release for version :' . PHP_EOL, 0);
        $version = $helper->ask($input, $output, $question);

        $question = new ConfirmationQuestion(
            'Continue with this action? (y/n) :' . PHP_EOL,
            false,
            '/^(y|j)/i'
        );
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        CLI::output($output,
            'Setting up release v' . $version . ' for ' . self::configGet('repo'), CLI::INFO, 0);

        CLI::output($output, 'Searching for stub: ' . Stubs::PREPARE, CLI::VERB, 1);

        $stub = Stubs::find(Stubs::PREPARE);

        CLI::output($output, 'Filling stub with data', CLI::VERB, 1);
        $stub = str_replace(
            [':repo', ':version'],
            [self::configGet('repo'), $version],
            $stub);


        if ($input->getOption('dry-run') !== false) {
            CLI::output($output, 'Push stub to php://stdout', CLI::INFO, 1);
            $output->write($stub);
        } else {
            CLI::output($output, 'Push stub to GitLab', CLI::INFO, 1);
            Gitlab::prepareReleaseIssue('Release version ' . $version, $stub);
        }
    }
}
