<?php

namespace Taskio\Controller;

use Taskio\Entity\TimeRow;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\Time;

class TimeRowController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/times/{user}/{yyyymm}", name="listTimeRow")
     * @param Request $request
     * @param null $user
     * @param null $yyyymm
     * @return Response
     */
    public function listAction(Request $request, $user = null, $yyyymm = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($yyyymm === null || $user === null) {
            return $this->redirectToRoute('listTimeRow', [
                'yyyymm' => date("Y-m"),
                'user' => $this->container->get('security.context')->getToken()->getUser()->getId()
            ]);
        } else {
            $dates = date("t", strtotime($yyyymm));

            return $this->render('timerow/grid-content.html.twig', [
                'yyyymm' => $yyyymm,
                'user' => $user,
                'dates' => $dates,
                'timeRowRepository' => $em->getRepository('Taskio:TimeRow')
            ]);
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/times/edit/{user}/{date}", name="editTimeRow")
     * @param Request $request
     * @param integer $user
     * @param string $date
     * @return Response
     * @throws \Exception
     */
    public function editAction(Request $request, $user, $date)
    {
        $timeUser = $this->getDoctrine()
            ->getRepository('Taskio:User')
            ->findOneBy(['id' => $user]);

        $timeRow = $this->getDoctrine()
            ->getRepository('Taskio:TimeRow')
            ->findOneBy(['user' => $timeUser, 'date' => new Chronos($date)]);

        if ($timeRow == null) {
            $timeRow = new TimeRow();
            $timeRow->setDate(new Chronos($date));
            $timeRow->setUser($timeUser);
        }

        $timeRow->setData(['modifiedAt' => new Chronos()]);

        $form = $this->createForm('Taskio\Form\TimeRowType', $timeRow);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($timeRow->getEndTime() < $timeRow->getStartTime()) {
                throw new \Exception("End cannot be later than start");
            }

            $this->persist($timeRow);

            return $this->redirectToRoute('listTimeRow', [
                'user' => $timeUser->getId(),
                'yyyymm' => date("Y-m", strtotime($date))
            ]);
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'timerow',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listTimeRow'),
            'list_name' => 'Time row list'
        ]);
    }
}
