<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    /**
     * @Route("/api/events", name="getEvents")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getEventsAction(Request $request)
    {
        /** @var EventRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Event::class);

        $response = array_map(function($event) {
            /** @var Event $event */
            return [
                'id' => $event->getId(),
                'name' => $event->getEntity(),
                'data' => $event->getData() ?? null,
                'datetime' => $event->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }, $repository->findBy([], ['createdAt' => 'DESC'], 15));

        return new JsonResponse($response);
    }
}
