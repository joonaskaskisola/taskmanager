<?php

namespace AppBundle\Seeds;

use AppBundle\Entity\Country;
use Soyuka\SeedBundle\Command\Seed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Soyuka\SeedBundle\Model\SeedInterface;

class CountrySeed extends Seed implements SeedInterface
{
    protected function configure()
    {
        $this->setSeedName('Country');
        parent::configure();
    }

    public function load(InputInterface $input, OutputInterface $output)
    {
        $this->disableDoctrineLogging();

        $countryRepository = $this->doctrine->getRepository('AppBundle:Country');

        $countries = array(
            array('name' => 'Finland', 'code' => 'FI', 'langCode' => 'fi_FI'),
            array('name' => 'Sweden', 'code' => 'SE', 'langCode' => 'se_SE'),
            array('name' => 'Poland', 'code' => 'PL', 'langCode' => 'pl_PL'),
            array('name' => 'Russia', 'code' => 'RU', 'langCode' => 'ru_RU'),
        );

        foreach ($countries as $country) {

            if ($countryRepository->findBy(array('code' => $country['code']))) {
                continue;
            }

            $e = new Country();

            $e->setName($country['name']);
            $e->setCode($country['code']);
            $e->setLangCode($country['langCode']);

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
        return 0;
    }
}
