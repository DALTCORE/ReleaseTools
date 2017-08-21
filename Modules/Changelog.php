<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use DALTCORE\ReleaseTools\Helpers\Exceptions\ChangelogExistsException;
use DALTCORE\ReleaseTools\Helpers\Git;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Changelog extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('changelog')
            // the short description shown while running "php bin/console list"
            ->setDescription('Add changelog entry')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Add a new changelog entry file')
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
        $question = new Question('What is the title of the merge request? [\'\'] :' . PHP_EOL, null);
        $title = $helper->ask($input, $output, $question);

        $helper = $this->getHelper('question');
        $question = new Question('Who is the author of this merge request? [' . Git::author() . '] :' . PHP_EOL,
            Git::author());
        $author = $helper->ask($input, $output, $question);

        $helper = $this->getHelper('question');
        $question = new Question('What is the ID of the merge request? [0] :' . PHP_EOL, 0);
        $mrId = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion(
            'Please select a type for this Merge Request : ' . PHP_EOL, [
            'New feature',
            'Bug fix',
            'Feature change',
            'New deprecation',
            'Feature removal',
            'Security fix',
            'Style fix',
            'Other',
        ],
            '7'
        );
        $type = $helper->ask($input, $output, $question);

        CLI::output($output, 'Creating changelog entry', CLI::INFO);

        CLI::output($output, 'Building YAML file', CLI::VERB, 1);
        $yaml = Yaml::dump([
            'title'         => $title,
            'author'        => $author,
            'merge_request' => $mrId,
            'type'          => $type
        ]);

        CLI::output($output, 'Building YAML file path', CLI::VERB, 1);
        $file = Constants::current_changelog();

        CLI::output($output, 'Check if file already exists', CLI::VERB, 1);
        $fs = new Filesystem();
        if ($fs->exists($file)) {
            CLI::output($output, 'About to throw up', CLI::VERB, 1);
            throw new ChangelogExistsException('Changelog already exists!');
        }

        CLI::output($output, 'Created changelog file', CLI::VERB, 2);

        if ($input->getOption('dry-run') !== false) {
            $output->write($yaml);
        } else {
            $fs->dumpFile($file, $yaml);
        }

    }
}
