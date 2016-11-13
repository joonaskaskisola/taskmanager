<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Unit;
use AppBundle\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UnitController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/unit/new", name="newUnit")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $unit = new Unit();

        $form = $this->createForm('AppBundle\Form\UnitType', $unit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($unit);
            $em->flush();

            return $this->redirectToRoute('listUnit');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/unit/edit/{id}", name="editUnit")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Unit $unit */
        $unit = $this->getDoctrine()
            ->getRepository('AppBundle:Unit')
            ->find($id);

        if (!$unit) {
            throw $this->createNotFoundException(
                'No unit found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\UnitType', $unit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($unit);
            $em->flush();

            return $this->redirectToRoute('listUnit');
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'unit',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listUnit'),
            'list_name' => 'Unit list'
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/units", name="listUnit")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $unitRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Unit');

        $units = $unitRepository->findAll();

        return $this->render('grid.html.twig', [
            'view' => 'unit',
            'units' => $units,
            'new' => $this->generateUrl('newUnit')
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/api/units", name="getUnits")
     * @param Request $request
     * @return JsonResponse
     */
    public function getUnitsAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Unit');

        $response = array_map(function($unit) {
            /** @var Unit $unit */
            return [
                'id' => $unit->getId(),
                'name' => $unit->getName()
            ];
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }
}
