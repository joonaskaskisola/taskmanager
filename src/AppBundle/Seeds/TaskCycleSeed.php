<?php

namespace AppBundle\Seeds;

use AppBundle\Entity\TaskCycle;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;

class TaskCycleSeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('TaskCycle');
        parent::configure();
    }

    public function load(InputInterface $input, OutputInterface $output)
    {
        $this->disableDoctrineLogging();

        $taskCycleRepository = $this->doctrine->getRepository('AppBundle:TaskCycle');

        $cycles = array(
            array(
                'name' => '1 day', 'hour' => 0, 'day' => 1, 'week' => 0, 'month' => 0
            ),
            array(
                'name' => '2 day', 'hour' => 0, 'day' => 2, 'week' => 0, 'month' => 0
            ),
            array(
                'name' => 'weekly', 'hour' => 0, 'day' => 0, 'week' => 1, 'month' => 0
            ),
            array(
                'name' => 'monthly', 'hour' => 0, 'day' => 0, 'week' => 0, 'month' => 1
            ),
            array(
                'name' => 'yearly', 'hour' => 0, 'day' => 0, 'week' => 0, 'month' => 12
            )
        );

        foreach ($cycles as $cycle) {

            if ($taskCycleRepository->findBy(array('name' => $cycle['name']))) {
                continue;
            }

            $e = new TaskCycle();

            $e->setName($cycle['name']);
            $e->setHour($cycle['hour']);
            $e->setDay($cycle['day']);
            $e->setWeek($cycle['week']);
            $e->setMonth($cycle['month']);

            $this->manager->persist($e);
        }

        $this->manager->flush();
        $this->manager->clear();
    }

    public function unload(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Unload taskCycle');
    }

    public function getOrder()
    {
        return 3;
    }
}
