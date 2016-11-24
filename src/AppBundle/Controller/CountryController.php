<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CountryController extends Controller
{
    /**
     * @Route("/country", name="listCountry")
     * @param Request $request
     * @return Response
     */
    public function listCountryAction(Request $request)
    {
        return $this->render('grid.html.twig', ['view' => 'country']);
    }

    /**
     * @Route("/api/country", name="getCountriesAction")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountriesAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Country');

        $response = array_map(function($country) {
            /** @var Country $country */
            return [
                'id' => $country->getId(),
                'name' => $country->getName() ?? "",
                'code' => $country->getCode() ?? "",
                'lang_code' => $country->getLangCode() ?? ""
            ];
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/country/{id}", name="getCountry")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountryAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Country');

        $response = array_map(function($country) {
            /** @var Country $country */
            return [
                'id' => $country->getId(),
                'name' => $country->getName() ?? "",
                'code' => $country->getCode() ?? "",
                'lang_code' => $country->getLangCode() ?? ""
            ];
        }, $repository->findBy(['id' => $id], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/country", name="editCountryAction")
     * @Method({"PUT", "POST"})
     */
    public function editCountryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Country');

        /** @var Country $country */
        $country = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : new Country();

        $country
            ->setName($request->request->get('name'))
            ->setCode($request->request->get('code'))
            ->setLangCode($request->request->get('lang_code'));

        $em->persist($country);
        $em->flush();

        return new JsonResponse();
    }
}
