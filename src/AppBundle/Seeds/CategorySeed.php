<?php

namespace AppBundle\Seeds;

use AppBundle\Entity\Category;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;
use Faker;

class CategorySeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('Category');
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

            $category = new Category();

            $category->setName($faker->word);

            $this->manager->persist($category);

            $this->manager->flush();
        }
    }

    public function unload(InputInterface $input, OutputInterface $output)
    {
    }

    public function getOrder()
    {
        return 1;
    }
}
