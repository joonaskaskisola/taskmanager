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
     * @Route("/item/new", name="newItem")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $item = new Item();

        $form = $this->createForm('AppBundle\Form\ItemType', $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($item);
            $em->flush();
            
            $customers = $this->getDoctrine()
                ->getRepository('AppBundle:Customer')
                ->findAll();
            
            foreach ($customers as $customer) {
                $cC = new CustomerItem();
                $cC->setItem($item)
                    ->setPrice(null)
                    ->setCustomer($customer);
                $em->persist($cC);
            }
            
            $em->flush();

            return $this->redirectToRoute('listItem');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Route("/item/edit/{id}", name="editItem")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Item $item */
        $item = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->find($id);

        if (!$item) {
            throw $this->createNotFoundException(
                'No item found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\ItemType', $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('listItem');
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'item',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listItem'),
            'list_name' => 'Item list'
        ]);
    }

    /**
     * @Route("/items", name="listItem")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        return $this->render('grid.html.twig', [
            'view' => 'item',
            'new' => $this->generateUrl('newItem')
        ]);
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
