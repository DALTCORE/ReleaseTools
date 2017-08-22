<?php

namespace DALTCORE\ReleaseTools\Modules;

use DALTCORE\ReleaseTools\Helpers\CLI;
use DALTCORE\ReleaseTools\Helpers\Constants;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Update extends Command
{

    protected $readyState = true;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('auto-update')
            // the short description shown while running "php bin/console list"
            ->setDescription('Auto updates the release-tool.phar')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Auto updates the release-tool.phar');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $dispatcher;

        $client = new Client([
            'base_uri'        => 'https://api.github.com/',
            'timeout'         => 30,
            'allow_redirects' => true,
        ]);


        $get = $client->get('repos/daltcore/releasetools/releases/latest');

        $release = \GuzzleHttp\json_decode($get->getBody()->getContents());

        if(isPhar() == false)
        {
            $version =  $release->tag_name;
        } else {
            $version = "@package_version@";
        }

        if($release->tag_name == $version)
        {
            CLI::output($output, 'You are already on the latest version of Release Tools ('.$version.')', CLI::INFO);
        } else {
            CLI::output($output, 'Downloading '.$release->tag_name.' to ./release-tool.phar now.', CLI::INFO);
            sleep(0.5);
            $client->request('GET', $release->assets[0]->browser_download_url, [
                'sink' => './release-tool.phar',
            ]);
            CLI::output($output, 'Download complete!', CLI::INFO);
        }

    }
}
