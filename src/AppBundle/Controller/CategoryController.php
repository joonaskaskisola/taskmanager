<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="listCategory")
     * @param Request $request
     * @return Response
     */
    public function listCategoryAction(Request $request)
    {
        return $this->render('grid.html.twig', ['view' => 'category']);
    }

    /**
     * @Route("/api/category", name="getCategoriesAction")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoriesAction(Request $request)
    {
        $serializer = $this->get('serializer');

        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');

        $response = array_map(function($category) use ($serializer) {
            /** @var Category $category */
            return json_decode($serializer->serialize($category, 'json'), true);
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }

    /**
     * @Route("/api/category/{id}", name="getCategory")
     * @param $id
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoryAction(Request $request, $id)
    {
        $serializer = $this->get('serializer');

        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');

        $response = array_map(function($category) use ($serializer) {
            /** @var Category $category */
            return json_decode($serializer->serialize($category, 'json'), true);
        }, $repository->findBy(['id' => $id]));

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/category", name="editCategoryAction")
     * @Method({"PUT", "POST"})
     */
    public function editCategoryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');

        /** @var Category $category */
        $category = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : new Category();

        $category
            ->setName($request->request->get('name'));

        $em->persist($category);
        $em->flush();

        return new JsonResponse();
    }
}
