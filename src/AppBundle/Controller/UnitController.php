<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Unit;
use AppBundle\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UnitController extends AbstractController
{
    /**
     * @Route("/api/unit", name="getUnitsAction")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getUnitsAction(Request $request)
    {
        $serializer = $this->get('serializer');

        /** @var UnitRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Unit');

        $response = array_map(function($unit) use ($serializer) {
            /** @var Unit $unit */
            return json_decode($serializer->serialize($unit, 'json'));
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/unit/{id}", name="getUnit")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getUnitAction(Request $request, $id)
    {
        $serializer = $this->get('serializer');

        /** @var UnitRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Unit');

        $response = array_map(function($unit) use ($serializer) {
            /** @var Unit $unit */
            return json_decode($serializer->serialize($unit, 'json'));
        }, $repository->findBy(['id' => $id]));

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/unit", name="editUnitAction")
     * @Method({"PUT", "POST"})
     */
    public function editUnitAction(Request $request)
    {
        /** @var UnitRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Unit');

        /** @var Unit $unit */
        $unit = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : new Unit();

        $unit
            ->setName($request->request->get('name'));

        $this->persist($unit);

        return new JsonResponse();
    }
}
