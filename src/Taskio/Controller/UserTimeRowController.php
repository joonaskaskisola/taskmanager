<?php

namespace Taskio\Controller;

use Taskio\Entity\UserTimeRow;
use Taskio\Repository\UserTimeRowRepository;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTimeRowController extends AbstractController
{
    /**
     * @Route("/usertime/status", name="statusUserTime")
     * @param Request $request
     * @return Response
     */
    public function statusAction(Request $request)
    {
        $userTimeRowRepository = $this->getDoctrine()
            ->getRepository('Taskio:UserTimeRow');

        /** @var UserTimeRow $userTimeRow */
        $userTimeRow = $userTimeRowRepository
            ->findOneBy([
                'user' => $this->container->get('security.context')->getToken()->getUser(),
                'endDateTime' => null
            ]);

        return new JsonResponse([
            'started' => ($userTimeRow === null ? false : true)
        ]);
    }

    /**
     * @Route("/usertime/start", name="startUserTime")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $now = new Chronos();

        /** @var UserTimeRowRepository $userTimeRowRepository */
        $userTimeRowRepository = $this->getDoctrine()
            ->getRepository('Taskio:UserTimeRow');

        /** @var UserTimeRow $userTimeRow */
        $userTimeRow = $userTimeRowRepository
            ->findOneBy([
                'user' => $this->container->get('security.context')->getToken()->getUser(),
                'endDateTime' => null
            ]);

        if ($userTimeRow !== null) {
            return new JsonResponse([
                'success' => false,
                'reason' => 'duplicate entry'
            ]);
        }

        $userTimeRow = new UserTimeRow();
        $userTimeRow
            ->setUser($this->container->get('security.context')->getToken()->getUser())
            ->setStartDateTime($now);

        $this->persist($userTimeRow);

        return new JsonResponse([
            'success' => true,
            'message' => 'Tracking started',
            'newIcon' => 'stop',
            'startTime' => $now
        ]);
    }

    /**
     * @Route("/usertime/end", name="endUserTime")
     * @param Request $request
     * @return Response
     */
    public function endAction(Request $request)
    {
        $now = new Chronos();

        /** @var UserTimeRowRepository $userTimeRowRepository */
        $userTimeRowRepository = $this->getDoctrine()
            ->getRepository('Taskio:UserTimeRow');

        /** @var UserTimeRow $userTimeRow */
        $userTimeRow = $userTimeRowRepository
            ->findOneBy([
                'user' => $this->container->get('security.context')->getToken()->getUser(),
                'endDateTime' => null
            ]);

        if ($userTimeRow === null) {
            return new JsonResponse([
                'success' => false,
                'reason' => 'no rows'
            ]);
        }

        $userTimeRow
            ->setEndDateTime($now);

        $diff = $userTimeRow
            ->getStartDateTime()
            ->diff($now);

        $diffFormatted = $diff->format("%H:%I:%S");

        $duration = $now->createFromFormat('H:i:s', $diffFormatted);

        $userTimeRow
            ->setDuration($duration);

        $this->persist($userTimeRow);

        return new JsonResponse([
            'success' => true,
            'message' => 'Tracking stopped',
            'newIcon' => 'play_arrow',
            'startTime' => $userTimeRow->getStartDateTime(),
            'endTime' => $now,
            'difference' => $diff
        ]);
    }
}
