<?php

namespace AppBundle\Seeds;

use AppBundle\Entity\User;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;
use Faker;

class UserSeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('User');
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

            $user = new User();

            $user
                ->setRoles(['ROLE_USER'])
                ->setEnabled(true)
                ->setCustomer(
                    $this->manager->getRepository('AppBundle:Customer')
                        ->findOneBy(
                            array(
                                'id' => 1,
                            )
                        )
                )
                ->setEmail($faker->email)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPassword('')
                ->setUsername($faker->userName)
                ->setCountry($randCountry)
                ->setPhone($faker->phoneNumber);

            $this->manager->persist($user);

            $this->manager->flush();
        }
    }

    public function unload(InputInterface $input, OutputInterface $output)
    {
    }

    public function getOrder()
    {
        return 7;
    }
}
