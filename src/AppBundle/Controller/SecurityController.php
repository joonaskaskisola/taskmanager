<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Faker;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $faker = Faker\Factory::create('fi_FI');

        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, 'moi');

        $tfaSecret = $this->container->get("scheb_two_factor.security.google_authenticator")->generateSecret();

        $user
            ->setGoogleAuthenticatorSecret($tfaSecret)
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled(true)
            ->setCustomer(
                $em->getRepository('AppBundle:Customer')->findOneBy(['id' => 1])
            )
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPassword($password)
            ->setUsername($faker->userName)
            ->setCountry(
                $em->getRepository('AppBundle:Country')->findOneBy(['id' => 1])
            )
            ->setPhone($faker->phoneNumber);

        $em->persist($user);
        $em->flush();

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'createdUser' => $user,
            'tfaSecret' => $tfaSecret,
            'tfaQr' => $this->container->get("scheb_two_factor.security.google_authenticator")->getUrl($user)
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
