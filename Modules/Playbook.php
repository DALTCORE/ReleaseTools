<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\Playbook\Parser;
use DALTCORE\ReleaseTools\Helpers\Playbooks;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Playbook extends Command
{

    protected $readyState = true;

    protected function configure()
    {
        $this->setName('playbook')
            ->setDescription('Test playbook by name')
            ->setHelp('Test playbook by name')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('playbook', InputArgument::REQUIRED, 'playbook name')
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

        $playbook = Playbooks::find($input->getArgument('playbook'));
        $playbook = new Parser($playbook, $input->getArguments(), $output, $input, $this);
    }
}
