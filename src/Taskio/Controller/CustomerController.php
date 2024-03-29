<?php

namespace Taskio\Controller;

use Taskio\Repository\CustomerRepository;
use Taskio\Service\Cache;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Taskio\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends AbstractController
{
	/**
	 * @Route("/api/customer", name="getCustomers")
	 * @Method({"GET"})
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getCustomersAction(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository('Taskio:Customer');

		$response = array_map(function ($customer) {
			/** @var Customer $customer */
			return [
				'id' => $customer->getId(),
				'name' => $customer->getName() ?? '',
				'businessId' => $customer->getBusinessId() ?? '',
				'streetAddress' => $customer->getStreetAddress() ?? '',
				'country' => $customer->getCountry() ?? '',
			];
		}, $repository->findBy([], ['name' => 'ASC']));

		return $this->jsonResponse($response, empty($response) ? 404 : null);
	}

	/**
	 * @Route("/api/customer/{id}", name="getCustomer")
	 * @param $id
	 * @Method({"GET"})
	 * @param Request $request
	 * @return Response
	 */
	public function getCustomerAction(Request $request, $id)
	{
		$serializer = $this->get('serializer');

		/** @var CustomerRepository $repository */
		$repository = $this->getDoctrine()->getRepository('Taskio:Customer');

		$response = array_map(function ($customer) use ($serializer) {
			/** @var Customer $customer */
			return json_decode($serializer->serialize($customer, 'json'), true);
		}, $repository->findBy(['id' => $id]));

		return $this->jsonResponse($response, empty($response) ? 404 : null);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse*
	 * @Route("/api/customer", name="editCustomerAction")
	 * @Method({"PUT", "POST"})
	 */
	public function editCustomerAction(Request $request)
	{
		/** @var CustomerRepository $repository */
		$repository = $this->getDoctrine()->getRepository('Taskio:Customer');

		/** @var Customer $customer */
		$customer = $request->request->get('id')
			? $repository->findOneBy(['id' => $request->request->get('id')])
			: (new Customer())->setCreatedAt(new Chronos());

		$customer->fill($request->request->all())
			->setModifiedAt(new Chronos());

		$this->persist($customer);

		return new JsonResponse();
	}
}
