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

        $fiCountry = $this->manager->getRepository('AppBundle:Country')
            ->findOneBy(
                array(
                    'langCode' => 'fi_FI',
                )
            );

        $user = new User();
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, 'admin');

        $user
            ->setTfaEnabled(false)
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled(true)
            ->setCustomer(
                $this->manager->getRepository('AppBundle:Customer')->findOneBy(['id' => 1])
            )
            ->setEmail("admin@localhost")
            ->setFirstName("Admin")
            ->setLastName("Administrator")
            ->setPassword($password)
            ->setUsername("admin")
            ->setCountry($fiCountry);

        $this->persist($user);

        if (getenv('SYMFONY_ENV') !== 'prod') {
            for ($i = 0; $i < 10; $i++) {
                $faker = Faker\Factory::create($fiCountry->getLangCode());

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
                    ->setCountry($fiCountry)
                    ->setPhone($faker->phoneNumber);

                $this->manager->persist($user);

                $this->manager->flush();
            }
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
