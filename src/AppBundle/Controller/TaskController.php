<?php

namespace AppBundle\Controller;

use AppBundle\Helper\FormHelper;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskController extends Controller
{
    /**
     * @Route("/task/new", name="newTask")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $task = new Task();

        $task->setCustomer($this->container->get('security.context')->getToken()->getUser()->getCustomer());
        $task->setNextAt(new Chronos());
        $task->setAmount(0);

        $form = $this->createForm('AppBundle\Form\TaskType', $task)
            ->add('task', 'entity', [
                'class' => 'AppBundle\Entity\Item',
                'choice_label' => 'name',
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $customerItem = $em->getRepository('AppBundle:CustomerItem')
                ->findOneBy([
                    'item' => $task->getTask()->getId(),
                    'customer' => $task->getCustomer()->getId()
                ]);

            $itemPrice = $task->getTask()->getPrice();
            $customerItemPrice = $customerItem->getPrice();

            if ($customerItemPrice !== null) {
                $task->setPrice($customerItemPrice);
            } else {
                $task->setPrice($itemPrice);
            }

            $task->setTotalPrice($task->getPrice() * $task->getAmount());

            $task->setTask($customerItem);
            $task->setCustomer($task->getCustomer());
            $task->setStatus($task->getStatus());
            $task->setCreatedAt(new Chronos());
            $task->setModifiedAt(null);

            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('listTask');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Route("/task/edit/{id}", name="editTask")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Task $task */
        $task = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $item = $em->getRepository('AppBundle:CustomerItem')
            ->findOneBy(['id' => $task->getTask()->getId()]);

        $task->setTask(
            $item->getItem()
        );

        $form = $this->createForm('AppBundle\Form\TaskType', $task)
            ->add('task', 'entity', [
                'class' => 'AppBundle:Item',
                'choice_label' => 'name',
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setTotalPrice($task->getPrice() * $task->getAmount());

            $customerItem = $em->getRepository('AppBundle:CustomerItem')
                ->findOneBy([
                    'item' => $task->getTask()->getId(),
                    'customer' => $task->getCustomer()->getId()
                ]);

            $task->setTask($customerItem);

            /** @var Task $oldTask */
            $oldTask = $em->getRepository('AppBundle:Task')->findOneById($task->getId());
            if ($oldTask->getNextTask() == null && $task->getStatus()->getId() == 3) {

                $newTask = new Task();
                $newTask
                    ->setNextAt(
                        $oldTask->getCycleTime()->getModifiedDate(
                            $oldTask->getNextAt()
                        )
                    )
                    ->setCreatedAt(new Chronos())
                    ->setStatus(
                        $em->getRepository('AppBundle:TaskStatus')->findOneBy(['id' => 1])
                    )
                    ->setTask($task->getTask())
                    ->setCustomer($task->getCustomer())
                    ->setCycleTime($task->getCycleTime())
                    ->setPrice($task->getPrice())
                    ->setAmount($task->getAmount())
                    ->setUser($task->getUser());

                $em->persist($newTask);
                $em->flush();

                $task->setNextTask($newTask);
            }

            $taskStatus = $em->getRepository('AppBundle:TaskStatus')->findOneById($task->getStatus());

            $task->setStatus($taskStatus);
            $task->setModifiedAt(new Chronos());

            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('listTask');
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'task',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listTask'),
            'list_name' => 'Task list'
        ]);
    }

    /**
     * @Route("/tasks/{searchBy}/{searchValue}", name="listTask")
     * @param Request $request
     * @param null|mixed $searchBy
     * @param null|mixed $searchValue
     * @return Response
     */
    public function listAction(Request $request, $searchBy = null, $searchValue = null)
    {
        $taskRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Task');

        switch ($searchBy) {
            case 'customer':
                $tasks = $taskRepository->findBy(['customer' => $searchValue, 'invoice' => NULL]);
                break;
            case 'status':
                $tasks = $taskRepository->findBy(['status' => $searchValue, 'invoice' => NULL]);
                break;
            case 'startedAt':
                $tasks = $taskRepository->findBy(['startedAt' => $searchValue, 'invoice' => NULL]);
                break;
            case 'user':
                $tasks = $taskRepository->findBy(['user' => $searchValue, 'invoice' => NULL]);
                break;
            default:
                $tasks = $taskRepository->findBy(['invoice' => NULL], ['nextAt' => 'DESC']);
        }

        return $this->render('grid.html.twig', [
            'view' => 'task',
            'tasks' => $tasks,
            'new' => $this->generateUrl('newTask')
        ]);
    }

    /**
     * @Route("/api/task/{id}", name="getTask")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCustomerAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Task');

        $response = array_map(function($task) {
            /** @var Task $task */
            return [
                'id' => $task->getId(),
            ];
        }, $repository->findBy(['id' => $id], ['name' => 'ASC']));

        return new JsonResponse($response);
    }
}
