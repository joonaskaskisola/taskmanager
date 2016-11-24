<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Route("/user", name="listUser")
     * @param Request $request
     * @return Response
     */
    public function listUserAction(Request $request)
    {
        return $this->render('grid.html.twig', ['view' => 'user']);
    }

    /**
     * @Route("/api/user", name="getUsersAction")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsersAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $response = array_map(function($user) {
            /** @var User $user */
            return [
                'id' => $user->getId(),
                'name' => sprintf("%s %s", $user->getFirstName(), $user->getLastName())
            ];
        }, $repository->findBy([], ['firstName' => 'ASC', 'lastName' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/user/{id}", name="getUser")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $response = array_map(function($user) {
            /** @var User $user */
            return [
                'id' => $user->getId(),
                'name' => sprintf("%s %s", $user->getFirstName(), $user->getLastName())
            ];
        }, $repository->findBy(['id' => $id], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/user", name="editUserAction")
     * @Method({"PUT", "POST"})
     */
    public function editUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        /** @var User $user */
        $user = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : new User();

        $user
            ->setFirstName($request->request->get('firstName'))
            ->setLastName($request->request->get('lastName'));

        $em->persist($user);
        $em->flush();

        return new JsonResponse();
    }
}
