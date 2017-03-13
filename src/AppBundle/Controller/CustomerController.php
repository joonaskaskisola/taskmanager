<?php

namespace AppBundle\Controller;

use AppBundle\Repository\CustomerRepository;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="listCustomer")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/api/customer", name="getCustomers")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCustomersAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Customer');

        $response = array_map(function($customer) {
            /** @var Customer $customer */
            return [
                'id' => $customer->getId(),
                'name' => $customer->getName() ?? '',
                'businessId' => $customer->getBusinessId() ?? '',
                'streetAddress' => $customer->getStreetAddress() ?? '',
                'country' => $customer->getCountry() ?? '',
            ];
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/customer/{id}", name="getCustomer")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCustomerAction(Request $request, $id)
    {
        $serializer = $this->get('serializer');

        /** @var CustomerRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Customer');

        $response = array_map(function($customer) use ($serializer) {
            /** @var Customer $customer */
            return json_decode($serializer->serialize($customer, 'json'), true);
        }, $repository->findBy(['id' => $id]));

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/customer", name="editCustomerAction")
     * @Method({"PUT", "POST"})
     */
    public function editCustomerAction(Request $request)
    {
//        $response = new JsonResponse();
//        $response->setStatusCode(422);
//        $response->setData([
//            'status' => 'error',
//            'error_fields' => [
//                'name' => 'Invalid name'
//            ]
//        ]);
//
//        return $response;

        /** @var CustomerRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Customer');

        /** @var Customer $customer */
        $customer = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : (new Customer())->setCreatedAt(new Chronos());

        $customer
            ->setName($request->request->get('name'))
            ->setName2($request->request->get('name2'))
            ->setLocality($request->request->get('locality'))
            ->setZipCode($request->request->get('zipCode'))
            ->setStreetAddress($request->request->get('streetAddress'))
            ->setBusinessId($request->request->get('businessId'))
            ->setContactPerson($request->request->get('contactPerson'))
            ->setEmail($request->request->get('email'))
            ->setCountry($request->request->get('country'))
            ->setModifiedAt(new Chronos());

        $this->persist($customer);

        return new JsonResponse();
    }
}
