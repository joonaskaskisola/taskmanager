<?php

namespace Taskio\Seeds;

use Taskio\Entity\Unit;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;

class UnitSeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('Unit');
        parent::configure();
    }

    public function load(InputInterface $input, OutputInterface $output)
    {
        $this->disableDoctrineLogging();

        $unitRepository = $this->doctrine->getRepository('Taskio:Unit');

        $groups = array(
            array('name' => 'hour(s)'),
            array('name' => 'copy(s)')
        );

        foreach ($groups as $group) {

            if ($unitRepository->findBy(array('name' => $group['name']))) {
                continue;
            }

            $e = new Unit();

            $e->setName($group['name']);

            $this->manager->persist($e);
        }

        $this->manager->flush();
        $this->manager->clear();
    }

    public function unload(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Unload unit');
    }

    public function getOrder()
    {
        return 5;
    }
}
