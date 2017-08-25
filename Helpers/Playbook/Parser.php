<?php

namespace DALTCORE\ReleaseTools\Helpers\Playbook;

use DALTCORE\ReleaseTools\Helpers\Exceptions\PlaybookSubjectMethodMissingException;
use DALTCORE\ReleaseTools\Helpers\Exceptions\PlaybookSubjectMissingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class Parser
{
    protected $playbook = [];
    protected $subjects = [];
    protected $arguments = [];
    protected $outputInterface;
    protected $inputInterface;
    protected $command;

    /**
     * Parser constructor.
     *
     * @param                                                   $playbook
     * @param null                                              $args
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param                                                   $command
     *
     * @throws \Exception
     */
    public function __construct($playbook, $args = null, OutputInterface $output, InputInterface $input, $command)
    {

        if ($args == null) {
            throw new \Exception('Something went wrong with $args on __construct');
        }

        $args = (new CommandParameters())->fromArray($args);
        $args->setIO($input, $output, $command);

        $this->arguments = $args;
        $this->outputInterface = $output;
        $this->inputInterface = $input;
        $this->command = $command;

        $arrays = [];

        if (stripos($playbook, ':version') !== false) {
            $arrays[':version'] = $args->version;
        }

        if (stripos($playbook, ':repo') !== false) {
            $arrays[':repo'] = ConfigReader::configGet('repo');
        }

        if (!empty($arrays)) {
            foreach ($arrays as $find => $replace) {
                $playbook = str_replace($find, $replace, $playbook);
            }
        }

        $this->playbook = Yaml::parse($playbook);

        $this->parsePlaybookContents();
        $this->walkPlaybook();

        return $this;
    }

    /**
     * Parse playbook contents into subjects
     */
    protected function parsePlaybookContents()
    {
        foreach ($this->playbook['playbook'] as $subject => $methods) {
            $this->subjects[$subject] = $methods;
        }
    }

    protected function walkPlaybook()
    {
        foreach ($this->subjects as $subject => $methods) {

            $subject = "\\DALTCORE\\ReleaseTools\\Helpers\\Playbook\\Subjects\\" . ucfirst($subject);

            if (!class_exists($subject)) {
                throw new PlaybookSubjectMissingException('Subject ' . $subject . ' not found!');
            }

            foreach ($methods as $method => $arguments) {
                $subject = new $subject();

                if (!method_exists($subject, $method)) {
                    throw new PlaybookSubjectMethodMissingException('Method  "' . $method . '" is missing from subject '
                        . get_class($subject));
                }

                $arguments = (new PlaybookParameters())->fromArray($arguments);
                $parameters = $this->arguments;
                $output = $this->outputInterface;
                $input = $this->inputInterface;

                $subject->$method($arguments, $parameters, $output, $input);
            }
        }
    }
}
