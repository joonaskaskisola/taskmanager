<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerNote;
use AppBundle\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CustomerNoteController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/customer/{customer}/note/new", name="newCustomerNote")
     * @param Request $request
     * @param integer $customer
     * @return Response
     */
    public function newAction(Request $request, $customer)
    {
        $em = $this->getDoctrine()->getManager();

        $customerNote = new CustomerNote();

        $customer = $em->getRepository('AppBundle:Customer')
            ->findOneBy(['id' => $customer]);

        $customerNote->setCustomer($customer);
        $customerNote->setCreatedAt(new \DateTime());
        $customerNote->setCreator($this->container->get('security.context')->getToken()->getUser());

        $form = $this->createForm('AppBundle\Form\CustomerNoteType', $customerNote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($customerNote);
            $em->flush();

            return $this->redirectToRoute('editCustomer', [
                'id' => $customerNote->getCustomer()->getId()
            ]);
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }
}
