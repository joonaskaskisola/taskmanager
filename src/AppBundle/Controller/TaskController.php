<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerItem;
use AppBundle\Entity\TaskStatus;
use AppBundle\Repository\CustomerItemRepository;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\TaskRepository;
use AppBundle\Repository\TaskStatusRepository;
use Cake\Chronos\Chronos;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    /**
     * @Route("/api/task", name="getTasks")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getTasksAction(Request $request)
    {
        if ($request->get('customerId')) {
            $search = ['customer' => $request->get('customerId')];
        }

        $serializer = $this->get('serializer');

        /** @var TaskRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Task');

        $response = array_map(function($task) use ($serializer) {
            /** @var Task $task */
            return json_decode($serializer->serialize($task, 'json'));
        }, $repository->findBy($search ?? [], ['createdAt' => 'ASC']));

        return $this->jsonResponse($response, empty($response) ? 404 : null);
    }

    /**
     * @Route("/api/task/{id}", name="getTask")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getTaskAction(Request $request, $id)
    {
        $serializer = $this->get('serializer');

        /** @var TaskRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Task');

        $response = array_map(function($task) use ($serializer) {
            /** @var Task $task */
            return json_decode($serializer->serialize($task, 'json'));
        }, $repository->findBy(['id' => $id]));

        return $this->jsonResponse($response, empty($response) ? 404 : null);
    }

    /**
     * @Route("/api/task", name="createNewTask")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function postTaskAction(Request $request)
    {
        $task = new Task();

        /** @var CustomerRepository $customerRepository */
        $customerRepository = $this->getDoctrine()->getRepository(Customer::class);
        $customer = $customerRepository->find(
            $request->request->get('customer')
        );

        /** @var CustomerItemRepository $customerItemRepository */
        $customerItemRepository = $this->getDoctrine()->getRepository(CustomerItem::class);

        /** @var CustomerItem $customerItem */
        $customerItem = $customerItemRepository->findOneBy([
            'customer' => $request->request->get('customer'),
            'item' => $request->request->get('name')
        ]);

        /** @var TaskStatusRepository $taskStatusRepository */
        $taskStatusRepository = $this->getDoctrine()->getRepository(TaskStatus::class);

        /** @var TaskStatus $taskStatus */
        $taskStatus = $taskStatusRepository->find(1);

        $task->fill($request->request->all())
            ->setCreatedAt(new Chronos())
            ->setCustomer($customer)
            ->setImportant(0)
            ->setUser($this->getUser())
            ->setCustomerItem($customerItem)
            ->setPrice($customerItem->getPrice())
            ->setStatus($taskStatus);

        $this->persist($task);

        return new Response();
    }
}
