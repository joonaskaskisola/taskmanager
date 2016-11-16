<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerItem;
use AppBundle\Helper\FormHelper;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomerController extends Controller
{
    /**
     * @Route("/customer/new", name="newCustomer")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $customer = new Customer();

        $form = $this->createForm('AppBundle\Form\CustomerType', $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $customer->setCreatedAt(new Chronos());
            $em->persist($customer);
            $em->flush();

            $catalogues = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findAll();

            foreach ($catalogues as $catalogue) {
                $cC = new CustomerItem();
                $cC->setItem($catalogue)
                    ->setPrice(null)
                    ->setCustomer($customer);
                $em->persist($cC);
            }

            $em->flush();

            return $this->redirectToRoute('listCustomer');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Route("/customer/edit/{id}", name="editCustomer")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Customer $customer */
        $customer = $this->getDoctrine()
            ->getRepository('AppBundle:Customer')
            ->find($id);

        if (!$customer) {
            throw $this->createNotFoundException(
                'No customer found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\CustomerType', $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('listCustomer');
        }

        $customerNoteRepository = $this->getDoctrine()->getRepository('AppBundle:CustomerNote');
        $customerNotes = $customerNoteRepository->findBy(['customer' => $customer]);

        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $customerUsers = $userRepository->findBy(['customer' => $customer]);

        $taskRepository = $this->getDoctrine()->getRepository('AppBundle:Task');
        $customerTasks = $taskRepository->findBy(['customer' => $customer]);

        return $this->render('customer/form.html.twig', [
            'form_layout' => 'customer',
            'form' => $form->createView(),
            'notes' => $customerNotes,
            'users' => $customerUsers,
            'tasks' => $customerTasks
        ]);
    }

    /**
     * @Route("/customers", name="listCustomer")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $customers = $this->getDoctrine()
            ->getRepository('AppBundle:Customer')
            ->findAll();

        return $this->render('grid.html.twig', [
            'form_layout' => 'customer',
            'view' => 'customer',
            'customers' => $customers,
            'new' => $this->generateUrl('newCustomer')
        ]);
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
                'name' => $customer->getName(),
                'businessId' => $customer->getBusinessId(),
                'streetAddress' => $customer->getStreetAddress(),
                'country' => $customer->getCountry(),
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:Customer');

        $response = array_map(function($customer) {
            /** @var Customer $customer */
            return [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'name2' => $customer->getName2(),
                'email' => $customer->getEmail(),
                'businessId' => $customer->getBusinessId(),
                'contactPerson' => $customer->getContactPerson(),
                'streetAddress' => $customer->getStreetAddress(),
                'locality' => $customer->getLocality(),
                'zipCode' => $customer->getZipCode(),
                'country' => $customer->getCountry(),
            ];
        }, $repository->findBy(['id' => $id], ['name' => 'ASC']));

        return new JsonResponse($response);
    }
}
