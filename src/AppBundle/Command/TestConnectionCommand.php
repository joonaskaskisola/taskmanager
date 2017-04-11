<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TestConnectionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
	$this->setName('app:test-connection');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$memcache = $this->getContainer()->get('memcached');
	$elasticsearch = $this->getContainer()->get('elasticsearch');
    }
}
