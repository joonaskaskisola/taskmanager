<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Faker\Factory;
use GuzzleHttp\Client;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * @Route("/api/tfa/qrcode", name="getUserTfaQrCode")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function getUserTfaQrCode(Request $request)
    {
        $client = new Client();
        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!$user->isTfaEnabled()) {
            $encoder = 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=';
            $res = $client->request(
                'GET',
                $encoder . urlencode(sprintf('otpauth://totp/%s?secret=%s', $user->getEmail(), $user->getTfaKey()))
            );

            return new Response($res->getBody(), 200, [
                'Content-type' => 'image/jpeg'
            ]);
        }

        return new Response();
    }

    /**
     * @Route("/api/tfa/verify", name="verifyTfa")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function verifyTfa(Request $request)
    {
        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!$user->isTfaEnabled()) {

            $googleAuthenticator = new \Google\Authenticator\GoogleAuthenticator();
            $isValid = $googleAuthenticator->checkCode(
                $user->getTfaKey(), $request->request->get('tfa_key')
            );

            return new JsonResponse(['isValid' => $isValid]);
        }

        return new JsonResponse();
    }

    /**
     * @Route("/api/tfa/enable", name="enableTfa")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function enableTfa(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        $user->setTfaEnabled(true);
        $em->persist($user);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/api/tfa/disable", name="disableTfa")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function disableTfa(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        $user->setTfaEnabled(false);
        $em->persist($user);
        $em->flush();

        return new JsonResponse();
    }
}
