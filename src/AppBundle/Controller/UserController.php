<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/api/profile", name="getProfileAction")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfileAction(Request $request)
    {
        $serializer = $this->get('serializer');

        /** @var UserRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $response = array_map(function($user) use ($serializer) {
            /** @var User $user */
            return json_decode($serializer->serialize($user, 'json'));
        }, $repository->findBy([
            'id' => $this->container->get('security.context')->getToken()->getUser()->getId()
        ]));

        return $this->jsonResponse($response, empty($response) ? 404 : null);
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

        return $this->jsonResponse($response, empty($response) ? 404 : null);
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

        return $this->jsonResponse($response, empty($response) ? 404 : null);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/user", name="editUserAction")
     * @Method({"PUT", "POST"})
     */
    public function editUserAction(Request $request)
    {
        /** @var UserRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        /** @var User $user */
        $user = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : new User();

        $user
            ->setFirstName($request->request->get('firstName'))
            ->setLastName($request->request->get('lastName'));

        $this->persist($user);

        return new JsonResponse();
    }

    /**
     * @Route("/api/user/new", name="newUserAction")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function newUserAction(Request $request)
    {
        $faker = Factory::create('fi_FI');

        $user = new User();
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, 'moi');

        $user
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled(true)
            ->setCustomer(
                $this->getDoctrine()->getRepository('AppBundle:Customer')->findOneBy(['id' => 1])
            )
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPassword($password)
            ->setUsername($faker->userName)
            ->setCountry(
                $this->getDoctrine()->getRepository('AppBundle:Country')->findOneBy(['id' => 1])
            )
            ->setPhone($faker->phoneNumber);

        $this->persist($user);

        return (new JsonResponse(['username' => $user->getUsername()]))->setStatusCode(201);
    }
}
