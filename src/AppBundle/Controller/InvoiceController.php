<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Repository\InvoiceRepository;
use Cake\Chronos\Chronos;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends AbstractController
{
    /**
     * @Route("/invoice/new", name="newInvoice")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $invoice = new Invoice();

        $form = $this->createFormBuilder($invoice)
            ->add('customer', 'entity', [
                'class' => 'AppBundle:Customer',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('reference', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $invoiceCustomer = $em->getRepository('AppBundle:Customer')->findOneById($invoice->getCustomer());

            $invoice->setCustomer($invoiceCustomer);
            $invoice->setCreatedAt(new Chronos());

            $this->persist($invoice);

            return $this->redirectToRoute('chooseInvoiceRows', [
                'invoiceId' => $invoice->getId(),
                'customerId' => $invoice->getCustomer()->getId()
            ]);
        }

        return $this->render('form.html.twig', FormHelper::getArray($form, [
            'list' => $this->generateUrl('listInvoice'),
            'list_name' => 'Invoice list'
        ]));
    }

    /**
     * @Route("/invoice/{invoiceId}/{customerId}/addTasks", name="chooseInvoiceRows")
     * @param Request $request
     * @param integer $invoiceId
     * @param integer $customerId
     * @return Response
     */
    public function chooseTasksAction(Request $request, $invoiceId, $customerId)
    {
        $taskRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Task');

        $tasks = $taskRepository->findBy(
            [
                'invoice' => NULL,
                'customer' => $customerId
            ],
            [
                'nextAt' => 'DESC'
            ]
        );

        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('AppBundle:Customer')->findOneById($customerId);
        $invoice = $em->getRepository('AppBundle:Invoice')->findOneById($invoiceId);

        return $this->render('invoice/list_tasks.html.twig', [
            'tasks' => $tasks,
            'invoice' => $invoice,
            'customer' => $customer,
            'new' => $this->generateUrl('homepage')
        ]);
    }

    /**
     * @Route("/invoice/{id}", name="showInvoice")
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        /** @var Invoice $invoice */
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($id);

        $invoiceRows = $this->getDoctrine()
            ->getRepository('AppBundle:InvoiceRow')
            ->findBy(['invoice' => $id]);

        if (!$invoice) {
            throw $this->createNotFoundException(
                'No invoice found for id '.$id
            );
        }

        return $this->render('invoice/view.html.twig', [
            'invoice' => $invoice,
            'rows' => $invoiceRows,
            'new' => $this->generateUrl('homepage')
        ]);
    }

    /**
     * @Route("/invoice/{invoiceId}/complete", name="completeInvoice")
     * @param Request $request
     * @param $invoiceId
     * @return Response
     */
    public function completeAction(Request $request, $invoiceId)
    {
        $tasks = $this->get('request')->request->get('tasks');

        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice');
        
        $invoiceRepository->setTasksPaid($invoiceId, $tasks);

        return $this->redirectToRoute('listInvoice');
    }

    /**
     * @Route("/invoice", name="listInvoice")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $invoiceRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice');

        $invoices = $invoiceRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('grid.html.twig', [
            'view' => 'invoice',
            'invoices' => $invoices,
            'new' => $this->generateUrl('newInvoice')
        ]);
    }
}
