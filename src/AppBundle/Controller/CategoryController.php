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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CategoryController extends AbstractController
{
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

        return $this->jsonResponse($response, empty($response) ? 404 : null);
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

        return $this->jsonResponse($response, empty($response) ? 404 : null);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/category", name="editCategoryAction")
     * @Method({"PUT", "POST"})
     */
    public function editCategoryAction(Request $request)
    {
        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');

        /** @var Category $category */
        $category = $request->request->get('id')
            ? $repository->findOneBy(['id' => $request->request->get('id')])
            : new Category();

        $category
            ->setName($request->request->get('name'));

        $this->persist($category);

        return new JsonResponse();
    }
}
