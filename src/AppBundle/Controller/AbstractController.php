<?php

namespace AppBundle\Controller;

use AppBundle\Repository\AbstractRepository;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\EventRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

abstract class AbstractController extends Controller
{
    protected function persist($object)
    {
        /** @var UserRepository|CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository(get_class($object));
        $repository->persist(
            $object,
            $this->get('security.token_storage')
                ->getToken()
                ->getUser()
        );
    }
}
