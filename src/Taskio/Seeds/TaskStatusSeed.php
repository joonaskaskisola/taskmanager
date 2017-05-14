<?php

namespace Taskio\Seeds;

use Taskio\Entity\TaskStatus;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;

class TaskStatusSeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('TaskStatus');
        parent::configure();
    }

    public function load(InputInterface $input, OutputInterface $output)
    {
        $this->disableDoctrineLogging();

        $taskStatusRepository = $this->doctrine->getRepository('Taskio:TaskStatus');

        $taskStatuses = array(
            array('id' => 1, 'name' => 'New'),
            array('id' => 2, 'name' => 'WIP'),
            array('id' => 3, 'name' => 'Completed'),
        );

        foreach ($taskStatuses as $taskStatus) {

            if ($taskStatusRepository->findBy(array('id' => $taskStatus['id']))) {
                continue;
            }

            $e = new TaskStatus();

            $e->setId($taskStatus['id']);
            $e->setName($taskStatus['name']);

            $this->manager->persist($e);
        }

        $this->manager->flush();
        $this->manager->clear();
    }

    public function unload(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Unload foo:bar');
    }

    public function getOrder()
    {
        return 4;
    }
}
