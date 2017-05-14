<?php

namespace Taskio\Seeds;

use Taskio\Entity\User;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;
use Faker;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

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

        $fiCountry = $this->manager->getRepository('Taskio:Country')
            ->findOneBy(
                array(
                    'langCode' => 'fi_FI',
                )
            );

        $user = new User();
        $passwordEncoder = new UserPasswordEncoder(
            new EncoderFactory(array(
                'FOS\\UserBundle\\Model\\UserInterface' => array(
                    'class' => 'Symfony\\Component\\Security\\Core\\Encoder\\BCryptPasswordEncoder',
                    'arguments' => array(0 => 13)
                ),
                'Taskio\\Entity\\User' => array(
                    'class' => 'Symfony\\Component\\Security\\Core\\Encoder\\BCryptPasswordEncoder',
                    'arguments' => array(0 => 13)
                )
            ))
        );

        $password = $passwordEncoder->encodePassword($user, 'admin');

        $user
            ->setTfaEnabled(false)
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled(true)
            ->setCustomer(
                $this->manager->getRepository('Taskio:Customer')->findOneBy(['id' => 1])
            )
            ->setEmail("admin@localhost")
            ->setFirstName("Admin")
            ->setLastName("Administrator")
            ->setPassword($password)
            ->setUsername("admin")
            ->setCountry($fiCountry);

        $this->manager->persist($user);
        $this->manager->flush();

        if (getenv('SYMFONY_ENV') !== 'prod') {
            for ($i = 0; $i < 10; $i++) {
                $faker = Faker\Factory::create($fiCountry->getLangCode());

                $user = new User();

                $user
                    ->setRoles(['ROLE_USER'])
                    ->setEnabled(true)
                    ->setCustomer(
                        $this->manager->getRepository('Taskio:Customer')
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
