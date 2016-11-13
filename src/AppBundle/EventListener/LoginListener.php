<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Ip;
use AppBundle\Entity\UserIpLog;
use Cake\Chronos\Chronos;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+

/**
 * Custom login listener.
 */
class LoginListener
{
    /**
     * @var securityContext
     */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine)
    {
        $this->securityContext = $securityContext;
        $this->em = $doctrine->getManager();
    }

    /**
     * Do the magic.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $event->getRequest();
        $user = $event->getAuthenticationToken()->getUser();

        $clientIpAddress = $request->getClientIp();

        $ipRepository = $this->em->getRepository(Ip::class);
        $ipAddress = $ipRepository->findOneBy(['address' => $clientIpAddress]);

        if (null === $ipAddress) {
            $ipAddress = new Ip();
            $ipAddress->setAddress($clientIpAddress);
            $ipAddress->setWhois(
                json_decode(
                    file_get_contents(
                        sprintf(
                            "http://ip-api.com/json/%s",
                            $clientIpAddress
                        )
                    ), true
                )
            );

            $this->em->persist($ipAddress);
            $this->em->flush();
        }

        $userIpLog = new UserIpLog();
        $userIpLog->setIp($ipAddress);
        $userIpLog->setUser($user);
        $userIpLog->setCreatedAt(new Chronos());

        $this->em->persist($userIpLog);
        $this->em->persist($userIpLog);

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            // user has just logged in
        }

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // user has logged in using remember_me cookie
        }
    }
}