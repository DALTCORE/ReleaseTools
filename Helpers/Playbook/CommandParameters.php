<?php

namespace DALTCORE\ReleaseTools\Helpers\Playbook;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CommandParameters extends Argument
{
    protected $input;
    protected $output;
    protected $command;

    public function __get($name)
    {
        if ($name == 'version' && !isset($this->arguments[$name])) {
            $this->arguments[$name] = $this->askForVersion();
        }

        return parent::__get($name);
    }

    public function setIO(InputInterface $input, OutputInterface $output, $command)
    {
        $this->input = $input;
        $this->output = $output;
        $this->command = $command;
    }

    protected function askForVersion()
    {
        $helper = $this->command->getHelper('question');
        $question = new Question('What is the version? :' . PHP_EOL, null);

        return $helper->ask($this->input, $this->output, $question);
    }
}
