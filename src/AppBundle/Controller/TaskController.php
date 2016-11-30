<?php

namespace AppBundle\Controller;

use AppBundle\Repository\TaskRepository;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * @Route("/tasks/{searchBy}/{searchValue}", name="listTask")
     * @param Request $request
     * @param null|mixed $searchBy
     * @param null|mixed $searchValue
     * @return Response
     */
    public function listAction(Request $request, $searchBy = null, $searchValue = null)
    {
        return $this->render('grid.html.twig', ['view' => 'task']);
    }

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

        return new JsonResponse($response);
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

        return new JsonResponse($response);
    }
}
