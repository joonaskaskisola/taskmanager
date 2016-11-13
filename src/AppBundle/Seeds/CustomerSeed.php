<?php

namespace AppBundle\Seeds;

use AppBundle\Entity\Customer;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;
use Faker;

class CustomerSeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('Customer');
        parent::configure();
    }

    public function load(InputInterface $input, OutputInterface $output)
    {
        $this->disableDoctrineLogging();

        for ($i = 0; $i < 10; $i++) {
            $randCountry = $this->manager->getRepository('AppBundle:Country')
                ->findOneBy(
                    array(
                        'langCode' => 'fi_FI',
                    )
                );

            $faker = Faker\Factory::create($randCountry->getLangCode());

            $customer = new Customer();

            $customer->setName($faker->company)
                ->setBusinessId($faker->numberBetween(10000000, 20000000))
                ->setCreatedAt($faker->dateTimeThisYear);

            $this->manager->persist($customer);

            $this->manager->flush();
        }
    }

    public function unload(InputInterface $input, OutputInterface $output)
    {
    }

    public function getOrder()
    {
        return 2;
    }
}
