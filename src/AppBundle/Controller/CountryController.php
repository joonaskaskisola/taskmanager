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
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/country/new", name="newCountry")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $country = new Country();

        $form = $this->createForm('AppBundle\Form\CountryType', $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('listCountry');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/country/edit/{id}", name="editCountry")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Country $country */
        $country = $this->getDoctrine()
            ->getRepository('AppBundle:Country')
            ->find($id);

        if (!$country) {
            throw $this->createNotFoundException(
                'No country found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\CountryType', $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('listCountry');
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'country',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listCountry'),
            'list_name' => 'Country list'
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/countries", name="listCountry")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $countryRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Country');

        $countries = $countryRepository->findAll();

        return $this->render('grid.html.twig', [
            'view' => 'country',
            'countries' => $countries,
            'new' => $this->generateUrl('newCountry')
        ]);
    }

    /**
     * @Route("/api/country", name="getCountry")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountryAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Country');

        $response = array_map(function($country) {
            /** @var Country $country */
            return [
                'id' => $country->getId(),
                'name' => $country->getName(),
                'code' => $country->getCode()
            ];
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/country", name="putCountry")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function putCountryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Country');
        /** @var Country $country */
        $country = $repository->findOneBy(['id' => $request->request->get('id')]);

        $country
            ->setName($request->request->get('name'))
            ->setCode($request->request->get('code'));

        $em->persist($country);
        $em->flush();

        return new JsonResponse();
    }
}
