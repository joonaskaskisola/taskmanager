<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerItem;
use AppBundle\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemController extends Controller
{
    /**
     * @Route("/items", name="listItem")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        return $this->render('grid.html.twig', ['view' => 'item']);
    }

    /**
     * @Route("/api/item", name="getItems")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getItemsAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Item');

        $response = array_map(function($item) {
            /** @var Item $item */
            return [
                'id' => $item->getId(),
                'category' => [
                    'id' => $item->getCategory()->getId(),
                    'name' => $item->getCategory()->getName()
                ],
                'unit' => [
                    'id' => $item->getUnit()->getId(),
                    'name' => $item->getUnit()->getName()
                ],
                'name' => $item->getName(),
                'price' => $item->getPrice()
            ];
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Item');

        $response = array_map(function($item) {
            /** @var Item $item */
            return [
                'id' => $item->getId(),
                'category' => [
                    'id' => $item->getCategory()->getId(),
                    'name' => $item->getCategory()->getName()
                ],
                'unit' => [
                    'id' => $item->getUnit()->getId(),
                    'name' => $item->getUnit()->getName()
                ],
                'name' => $item->getName(),
                'price' => $item->getPrice()
            ];
        }, $repository->findBy(['id' => $id], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/item", name="putItems")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function putItemsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
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

        $em->persist($item);
        $em->flush();

        return new JsonResponse();
    }
}
