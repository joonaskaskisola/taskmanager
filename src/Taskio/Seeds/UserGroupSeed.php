<?php

namespace Taskio\Seeds;

use Taskio\Entity\UserGroup;
use Taskio\Repository\UserGroupRepository;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;

class UserGroupSeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('UserGroup');
        parent::configure();
    }

    public function load(InputInterface $input, OutputInterface $output)
    {
        $this->disableDoctrineLogging();

        $userGroupRepository = $this->doctrine->getRepository('Taskio:UserGroup');

        $groups = array(
            array('name' => 'Administrator', 'code' => 'ROLE_ADMIN'),
            array('name' => 'User', 'code' => 'ROLE_USER')
        );

        foreach ($groups as $group) {

            if ($userGroupRepository->findBy(array('code' => $group['code']))) {
                continue;
            }

            $e = new UserGroup();

            $e->setName($group['name']);
            $e->setCode($group['code']);

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
        return 6;
    }
}
