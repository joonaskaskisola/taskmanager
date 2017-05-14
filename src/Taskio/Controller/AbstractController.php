<?php

namespace Taskio\Controller;

use Taskio\Entity\User;
use Taskio\Repository\AbstractRepository;
use Taskio\Repository\CategoryRepository;
use Taskio\Repository\EventRepository;
use Taskio\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractController extends Controller
{
    protected function persist($object)
    {
        /** @var UserRepository|CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository(get_class($object));

        /** @var null|UsernamePasswordToken $token */
        if ($token = $this->get('security.token_storage')->getToken()) {
            $user = $token->getUser();
        }

        $repository->persist(
            $object,
            $user ?? (new User())
        );
    }

    /**
     * @param $data
     * @param $statusCode
     * @return JsonResponse
     */
    public function jsonResponse($data, $statusCode)
    {
        $response = new JsonResponse();
        $response->setStatusCode($statusCode ?? 200);

        return $response->setData(
            ($statusCode === 404
                ? ['message' => 'not found']
                : $data)
        );
    }
}
