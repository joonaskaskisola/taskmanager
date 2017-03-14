<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Cake\Chronos\Chronos;
use Doctrine\ORM\EntityRepository;
use ReflectionClass;

/**
 * Class AbstractRepository
 * @package AppBundle\Repository
 */
abstract class AbstractRepository extends EntityRepository
{
    /**
     * Persist the object
     *
     * @param object $object
     * @param User|null $user
     */
    public function persist($object, User $user = null)
    {
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();

        if (method_exists($object, 'getId')) {
            $reflect = new ReflectionClass($object);

            $event = new Event();
            $event->setCreatedAt(new Chronos());
            $event->setEntity($reflect->getShortName());
            $event->setData([
                'id' => $object->getId(),
                'name' => method_exists($object, 'getName')
                    ? $object->getName()
                    : ($reflect->getShortName() == 'User'
                        ? $object->getUsername()
                        : 'unknown'),
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername()
                ]
            ]);

            $em->persist($event);
            $em->flush();
        }
    }
}
