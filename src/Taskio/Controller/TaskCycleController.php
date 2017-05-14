<?php

namespace Taskio\Controller;

use Taskio\Entity\TaskCycle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TaskCycleController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/taskCycle/new", name="newTaskCycle")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $taskCycle = new TaskCycle();
        $taskCycle->setHour(0);
        $taskCycle->setDay(0);
        $taskCycle->setWeek(0);
        $taskCycle->setMonth(0);

        $form = $this->createForm('Taskio\Form\TaskCycleType', $taskCycle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persist($taskCycle);

            return $this->redirectToRoute('listTaskCycle');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/taskCycle/edit/{id}", name="editTaskCycle")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var TaskCycle $taskCycle */
        $taskCycle = $this->getDoctrine()
            ->getRepository('Taskio:TaskCycle')
            ->find($id);

        if (!$taskCycle) {
            throw $this->createNotFoundException(
                'No taskCycle found for id '.$id
            );
        }

        $form = $this->createForm('Taskio\Form\TaskCycleType', $taskCycle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persist($taskCycle);

            return $this->redirectToRoute('listTaskCycle');
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'task_cycle',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listTaskCycle'),
            'list_name' => 'Task cycle list'
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/taskCycles", name="listTaskCycle")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $taskCycleRepository = $this->getDoctrine()
            ->getRepository('Taskio:TaskCycle');

        $taskCycles = $taskCycleRepository->findAll();

        return $this->render('grid.html.twig', [
            'view' => 'task_cycle',
            'taskCycles' => $taskCycles,
            'new' => $this->generateUrl('newTaskCycle')
        ]);
    }
}
