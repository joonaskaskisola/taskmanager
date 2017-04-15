<?php

namespace AppBundle\Command;

use Cloudinary\Uploader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $memcache = $this->getContainer()->get('memcached');
//        $elasticsearch = $this->getContainer()->get('elasticsearch');

//        $cloudinary = $this->getContainer()->get('cloudinary');
        var_dump(Uploader::upload('https://c1.staticflickr.com/6/5337/8940995208_5da979c52f.jpg'));
    }
}
