<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerItem;
use AppBundle\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemController extends AbstractController
{
    /**
     * @Route("/api/item", name="getItems")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function getItemsAction(Request $request)
    {
        $serializer = $this->get('serializer');

        /** @var ItemRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Item');

        $response = array_map(function($item) use ($serializer) {
            /** @var Item $item */
            return json_decode($serializer->serialize($item, 'json'), true);
        }, $repository->findBy([], ['name' => 'ASC']));

        return $this->jsonResponse($response, empty($response) ? 404 : null);
    }

    /**
     * @Route("/api/item/{id}", name="getItemAction")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getItemAction(Request $request, $id)
    {
        $serializer = $this->get('serializer');

        /** @var ItemRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Item');

        $response = array_map(function($item) use ($serializer) {
            /** @var Item $item */
            return json_decode($serializer->serialize($item, 'json'), true);
        }, $repository->findBy(['id' => $id]));

        return $this->jsonResponse($response, empty($response) ? 404 : null);
    }

    /**
     * @Route("/api/item", name="putItems")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function putItemsAction(Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Item');

        /** @var Item $item */
        $item = $repository->findOneBy(['id' => $request->request->get('id')]);

        $item
            ->setName($request->request->get('name'))
            ->setPrice($request->request->get('price'))
            ->setCategory(
                $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Category')
                    ->findOneBy([
                        'id' => $request->request->get('category')
                    ])
            )
            ->setUnit(
                $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Unit')
                    ->findOneBy([
                        'id' => $request->request->get('unit')
                    ])
            );

        $this->persist($item);

        return new JsonResponse();
    }

    /**
     * @Route("/api/item", name="postItem")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function postItemAction(Request $request)
    {
        $item = new Item();
        $item
            ->setName($request->request->get('name'))
            ->setPrice($request->request->get('price'))
            ->setCategory(
                $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Category')
                    ->findOneBy([
                        'id' => $request->request->get('category')
                    ])
            )
            ->setUnit(
                $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Unit')
                    ->findOneBy([
                        'id' => $request->request->get('unit')
                    ])
            );

        $this->persist($item);

        $this->get('old_sound_rabbit_mq.app_producer')->publish(serialize([
            'event' => 'new_item',
            'entity' => $item
        ]));

        return new JsonResponse();
    }
}
