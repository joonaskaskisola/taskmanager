<?php

namespace Taskio\Controller;

use Taskio\Entity\Media;
use Taskio\Entity\User;
use Taskio\Repository\UserRepository;
use Taskio\Service\Cloudinary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Faker;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $faker = Faker\Factory::create('fi_FI');

        $user = new User();
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, 'moi');

        $tfaSecret = $this->container->get("scheb_two_factor.security.google_authenticator")->generateSecret();

        $user
            ->setTfaEnabled(false)
            ->setGoogleAuthenticatorSecret($tfaSecret)
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled(true)
            ->setCustomer(
                $this->getDoctrine()->getRepository('Taskio:Customer')->findOneBy(['id' => 1])
            )
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPassword($password)
            ->setUsername($faker->userName)
            ->setCountry(
                $this->getDoctrine()->getRepository('Taskio:Country')->findOneBy(['id' => 1])
            );

        $this->persist($user);

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'createdUser' => $user
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction(Request $request)
    {
        return $this->redirectToRoute('login');
    }
}
